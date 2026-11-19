<?php

namespace Tests\Feature;

use App\Models\District;
use App\Models\Eatery;
use App\Models\Feature;
use App\Models\Permit;
use App\Models\Review;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class KaonTaDavaoTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeders_create_personalized_data(): void
    {
        $this->seed(DatabaseSeeder::class);

        $eatery = Eatery::where('slug', 'carinderia-ni-asjali')->firstOrFail();

        $this->assertSame('DVO-2026-0523', $eatery->permit->permit_no);
        $this->assertSame('2027-05-23', $eatery->permit->expires_on->toDateString());
        $this->assertTrue($eatery->features->contains('name', 'Suki ni Asjali'));
        $this->assertTrue($eatery->is_open);
        $this->assertDatabaseHas('users', ['email' => 'asjali@kaonta.test']);
        $this->assertDatabaseHas('users', ['email' => 'tita.asjali@kaonta.test']);
    }

    public function test_schema_matches_spec(): void
    {
        $this->assertTrue(Schema::hasColumns('eateries', ['district_id', 'name', 'slug', 'address', 'price_range', 'specialty', 'is_open']));
        $this->assertTrue(Schema::hasColumns('permits', ['eatery_id', 'permit_no', 'expires_on']));
        $this->assertTrue(Schema::hasColumns('dishes', ['eatery_id', 'name', 'price', 'is_bestseller']));
        $this->assertSame(['eatery_id', 'feature_id'], Schema::getColumnListing('eatery_feature'));
    }

    public function test_index_and_show_pages_render_with_site_title(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->get(route('eateries.index'))
            ->assertOk()
            ->assertSee("Kaon Ta! Davao – Asjali's Food Guide", false);

        $this->get(route('eateries.show', 'carinderia-ni-asjali'))
            ->assertOk()
            ->assertSee('DVO-2026-0523')
            ->assertSee('Bestseller');
    }

    public function test_feature_query_filters_the_index(): void
    {
        $feature = Feature::factory()->create(['name' => 'Halal']);
        $tagged = Eatery::factory()->create(['name' => 'Tagged Place']);
        Eatery::factory()->create(['name' => 'Untagged Place']);
        $tagged->features()->attach($feature);

        $this->get(route('eateries.index', ['feature' => 'Halal']))
            ->assertOk()
            ->assertSee('Tagged Place')
            ->assertDontSee('Untagged Place');
    }

    public function test_district_page_shows_review_count_through_eateries(): void
    {
        $district = District::factory()->create(['name' => 'Agdao']);
        $eatery = Eatery::factory()->for($district)->create();
        Review::factory(3)->for($eatery)->create();

        $this->assertCount(3, $district->reviews);
        $this->get(route('districts.show', $district))
            ->assertOk()
            ->assertSee('3 reviews in Agdao');
    }

    public function test_reviewer_page_lists_their_reviews(): void
    {
        $user = User::factory()->create(['name' => 'Juan Reviewer']);
        $eatery = Eatery::factory()->create(['name' => 'Reviewed Place']);
        Review::factory()->for($eatery)->for($user)->create();

        $this->get(route('reviewers.show', $user))
            ->assertOk()
            ->assertSee('Juan Reviewer')
            ->assertSee('Reviewed Place');
    }

    public function test_review_is_validated_with_custom_message_and_keeps_old_input(): void
    {
        $eatery = Eatery::factory()->create();

        $this->from(route('eateries.show', $eatery))
            ->post(route('eateries.reviews.store', $eatery), ['body' => 'short', 'rating' => 3])
            ->assertRedirect(route('eateries.show', $eatery))
            ->assertSessionHasErrors(['user_id', 'body'])
            ->assertSessionHasInput('body', 'short');
    }

    public function test_review_is_stored_through_relationship(): void
    {
        $eatery = Eatery::factory()->create();
        $user = User::factory()->create();

        $this->post(route('eateries.reviews.store', $eatery), [
            'user_id' => $user->id,
            'rating' => 5,
            'body' => 'Sarap kaayo ang tinola!',
        ])->assertRedirect(route('eateries.show', $eatery));

        $this->assertDatabaseHas('reviews', ['eatery_id' => $eatery->id, 'user_id' => $user->id]);
    }

    public function test_eatery_can_be_created_updated_with_permit_and_features(): void
    {
        $district = District::factory()->create();
        $features = Feature::factory(2)->create();

        $payload = [
            'district_id' => $district->id,
            'name' => 'Carinderia ni Test',
            'address' => 'Test St, Davao City',
            'price_range' => 'mid',
            'is_open' => '1',
            'permit_no' => 'DVO-2026-9999',
            'expires_on' => now()->addYear()->toDateString(),
            'features' => $features->pluck('id')->all(),
        ];

        $this->post(route('eateries.store'), $payload)
            ->assertRedirect(route('eateries.show', 'carinderia-ni-test'));

        $eatery = Eatery::where('slug', 'carinderia-ni-test')->firstOrFail();
        $this->assertCount(2, $eatery->features);
        $this->assertSame('DVO-2026-9999', $eatery->permit->permit_no);

        $this->put(route('eateries.update', $eatery), [
            ...$payload,
            'permit_no' => 'DVO-2026-1111',
            'features' => [$features->first()->id],
        ])->assertRedirect();

        $eatery->refresh();
        $this->assertSame('DVO-2026-1111', $eatery->permit->permit_no);
        $this->assertCount(1, $eatery->features);
    }

    public function test_invalid_price_range_is_rejected(): void
    {
        $this->post(route('eateries.store'), ['price_range' => 'cheap'])
            ->assertSessionHasErrors(['price_range', 'name', 'district_id']);
    }

    public function test_home_shows_counts_and_top_three_rated(): void
    {
        $user = User::factory()->create();
        foreach ([5, 4, 3, 2] as $rating) {
            Review::factory()->for(Eatery::factory()->create(['name' => "Rated {$rating} Place"]))->for($user)->create(['rating' => $rating]);
        }
        Eatery::factory()->create(['name' => 'Unreviewed Place']);

        $this->get(route('home'))
            ->assertOk()
            ->assertSeeInOrder(['Top Rated', 'Rated 5 Place', 'Rated 4 Place', 'Rated 3 Place'])
            ->assertDontSee('Rated 2 Place')
            ->assertDontSee('Unreviewed Place');
    }

    public function test_index_paginates_six_and_combines_filters(): void
    {
        $district = District::factory()->create();
        Eatery::factory(7)->for($district)->create();
        Eatery::factory()->create(['name' => 'Sisig Hub', 'specialty' => 'Sisig']);

        $this->get(route('eateries.index'))->assertOk()->assertViewHas('eateries', fn ($page) => $page->perPage() === 6 && $page->count() === 6);

        $this->get(route('eateries.index', ['q' => 'sisig', 'district' => $district->id]))
            ->assertOk()
            ->assertSee('No eateries match your filters.');

        $this->get(route('eateries.index', ['q' => 'sisig']))->assertOk()->assertSee('Sisig Hub');
    }

    public function test_unknown_slug_returns_404(): void
    {
        $this->get('/eateries/does-not-exist')->assertNotFound();
    }

    public function test_closed_eatery_pauses_reviews(): void
    {
        $eatery = Eatery::factory()->closed()->create();
        $user = User::factory()->create();

        $this->get(route('eateries.show', $eatery))->assertSee('This eatery is closed. Reviews are paused.');
        $this->post(route('eateries.reviews.store', $eatery), ['user_id' => $user->id, 'rating' => 4, 'body' => 'Nice place indeed'])
            ->assertForbidden();
    }

    public function test_review_can_be_deleted(): void
    {
        $review = Review::factory()->create();

        $this->delete(route('reviews.destroy', $review))->assertRedirect(route('eateries.show', $review->eatery));
        $this->assertModelMissing($review);
    }

    public function test_expired_permit_shows_notice(): void
    {
        $eatery = Eatery::factory()->create();
        Permit::factory()->expired()->for($eatery)->create();

        $this->get(route('eateries.show', $eatery))->assertSee('Permit expired');
    }

    public function test_expired_permit_state(): void
    {
        $this->assertTrue(Permit::factory()->expired()->create()->isExpired());
    }
}
