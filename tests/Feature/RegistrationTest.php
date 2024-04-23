<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

class RegistrationTest extends TestCase
{
	private User $user;

	use RefreshDatabase;

	protected function setUp(): void
	{
		parent::setUp();
		$this->user = User::factory()->create([
			'email'             => 'nikaloSingle@gmail.com',
			'password'          => '1234',
			'email_verified_at' => null,
		]);
		$this->user->username = 'nikaloSingle';
		$this->user->save();
	}

	//guest middleware + success response if all data was valid
	public function test_register_page_should_create_user_if_all_correct_inputs_provided()
	{
		$response = $this->post('/api/register', [
			'username'         => 'nikk',
			'email'            => 'nikalo@mail.com',
			'password'         => '1234',
			'confirm_password' => '1234',
			'terms'            => 'true',
		]);

		$this->assertDatabaseHas('users', [
			'username'         => 'nikk',
			'email'            => 'nikalo@mail.com',
		]);
		$response->assertJson([
			'status' => 'VERIFICATION_LINK_SENT',
		]);
		$response->assertStatus(200);
	}

	public function test_register_page_shouldnt_be_accessible_for_auth_user()
	{
		$response = $this->actingAs($this->user)->post('/api/register', [
			'username'         => 'nikalo',
			'email'            => 'nikalo@gmail.com',
			'password'         => '1234',
			'confirm_password' => '1234',
			'terms'            => 'true',
		]);
		$response->assertRedirect('/');
	}

	// username validations
	public function test_register_page_should_return_wrong_input_error_if_username_not_provided()
	{
		$response = $this->post('/api/register', [
			'email'            => 'nikalo@gmail.com',
			'password'         => '1234',
			'confirm_password' => '1234',
			'terms'            => 'true',
		]);
		$response->assertJson([
			'status' => 'REGISTRATION_WRONG_INPUT',
		]);
		$response->assertStatus(422);
	}

	public function test_register_page_should_return_wrong_input_error_if_username_already_exists()
	{
		$response = $this->post('/api/register', [
			'username'         => 'nikaloSingle',
			'email'            => 'nikalo@gmail.com',
			'password'         => '1234',
			'confirm_password' => '1234',
			'terms'            => 'true',
		]);
		$response->assertJson([
			'status' => 'REGISTRATION_WRONG_INPUT',
		]);
		$response->assertStatus(422);
	}

	public function test_register_page_should_return_wrong_input_error_if_less_than_three_chars()
	{
		$response = $this->post('/api/register', [
			'username'         => 'ni',
			'email'            => 'nikalo@gmail.com',
			'password'         => '1234',
			'confirm_password' => '1234',
			'terms'            => 'true',
		]);
		$response->assertJson([
			'status' => 'REGISTRATION_WRONG_INPUT',
		]);
		$response->assertStatus(422);
	}

	// email validations
	public function test_register_page_should_return_wrong_input_error_if_email_not_provided()
	{
		$response = $this->post('/api/register', [
			'username'         => 'nikk',
			'password'         => '1234',
			'confirm_password' => '1234',
			'terms'            => 'true',
		]);
		$response->assertJson([
			'status' => 'REGISTRATION_WRONG_INPUT',
		]);
		$response->assertStatus(422);
	}

	public function test_register_page_should_return_wrong_input_error_if_email_wrong_format()
	{
		$response = $this->post('/api/register', [
			'username'         => 'nikk',
			'email'            => 'nikalomail.com',
			'password'         => '1234',
			'confirm_password' => '1234',
			'terms'            => 'true',
		]);
		$response->assertJson([
			'status' => 'REGISTRATION_WRONG_INPUT',
		]);
		$response->assertStatus(422);
	}

	public function test_register_page_should_return_wrong_input_error_if_email_already_exists()
	{
		$response = $this->post('/api/register', [
			'username'         => 'nikk',
			'email'            => 'nikaloSingle@gmail.com',
			'password'         => '1234',
			'confirm_password' => '1234',
			'terms'            => 'true',
		]);
		$response->assertJson([
			'status' => 'REGISTRATION_WRONG_INPUT',
		]);
		$response->assertStatus(422);
	}

	//password validations
	public function test_register_page_should_return_wrong_input_error_if_password_not_provided()
	{
		$response = $this->post('/api/register', [
			'username'         => 'nikk',
			'email'            => 'nikalo@mail.com',
			'confirm_password' => '1234',
			'terms'            => 'true',
		]);
		$response->assertJson([
			'status' => 'REGISTRATION_WRONG_INPUT',
		]);
		$response->assertStatus(422);
	}

	public function test_register_page_should_return_wrong_input_error_if_password_less_than_three_chars()
	{
		$response = $this->post('/api/register', [
			'username'         => 'nikk',
			'email'            => 'nikalo@mail.com',
			'password'         => '14',
			'confirm_password' => '1234',
			'terms'            => 'true',
		]);
		$response->assertJson([
			'status' => 'REGISTRATION_WRONG_INPUT',
		]);
		$response->assertStatus(422);
	}

	// password confirmation validations
	public function test_register_page_should_return_wrong_input_error_if_pass_confirmed_not_provided()
	{
		$response = $this->post('/api/register', [
			'username'         => 'nikk',
			'email'            => 'nikalo@mail.com',
			'password'         => '1234',
			'terms'            => 'true',
		]);
		$response->assertJson([
			'status' => 'REGISTRATION_WRONG_INPUT',
		]);
		$response->assertStatus(422);
	}

	public function test_register_page_should_return_wrong_input_error_if_passes_dont_match()
	{
		$response = $this->post('/api/register', [
			'username'         => 'nikk',
			'email'            => 'nikalo@mail.com',
			'password'         => '1234',
			'confirm_password' => '1834',
			'terms'            => 'true',
		]);
		$response->assertJson([
			'status' => 'REGISTRATION_WRONG_INPUT',
		]);
		$response->assertStatus(422);
	}

	//terms vadiation
	public function test_register_page_should_return_wrong_input_error_if_terms_not_checked()
	{
		$response = $this->post('/api/register', [
			'username'         => 'nikk',
			'email'            => 'nikalo@mail.com',
			'password'         => '1234',
			'confirm_password' => '1234',
			'terms'            => 'false',
		]);
		$response->assertJson([
			'status' => 'REGISTRATION_WRONG_INPUT',
		]);
		$response->assertStatus(422);
	}

	public function test_register_page_should_send_verification_email_if_all_correct_inputs_provided()
	{
		Notification::fake();
		$this->post('/api/register', [
			'username'         => 'nikk',
			'email'            => 'nikalo@mail.com',
			'password'         => '1234',
			'confirm_password' => '1234',
			'terms'            => 'true',
		]);

		Notification::assertSentTo(User::where('email', 'nikalo@mail.com')->first(), VerifyEmail::class);

		$this->assertDatabaseHas('users', [
			'email' => 'nikalo@mail.com',
		]);
	}

	public function test_verification_link_verified()
	{
		Notification::fake();
		$this->post('/api/register', [
			'username'         => 'newUser',
			'email'            => 'newUser@mail.com',
			'password'         => '1234',
			'confirm_password' => '1234',
			'terms'            => 'true',
		]);
		$temporaryUrl = '';
		Notification::assertSentTo(User::where('email', 'newUser@mail.com')->first(), VerifyEmail::class, function (VerifyEmail $notification) use (&$temporaryUrl) {
			$temporaryUrl = $notification->toMail(User::where('email', 'newUser@mail.com')->first())->actionUrl;
			$temporaryUrl = str_replace('http://127.0.0.1:5173/login', '/api', $temporaryUrl);
			return true;
		});

		$this->get($temporaryUrl)->assertJson(
			[
				'status' => 'VERIFICATION_SUCCESS',
			]
		);
		$this->assertNotNull(User::where('email', 'newUser@mail.com')->first()->fresh()->email_verified_at);
	}
}
