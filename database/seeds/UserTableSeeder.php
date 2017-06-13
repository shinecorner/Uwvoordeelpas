<?php
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = Sentinel::registerAndActivate([
		    'email'    => 'user@user.com',
		    'password' => 'user123',
		]);

        $company = Sentinel::registerAndActivate([
		    'email'    => 'company@company.com',
		    'password' => 'company123',
		]);

        $admin = Sentinel::registerAndActivate([
		    'email'    => 's.seymor@live.nl',
		    'password' => 'admin123',
		]);

		$role = Sentinel::getRoleRepository()->createModel()->create([
		    'name' => 'Admin',
		    'slug' => 'admin',
		]);

		$role = Sentinel::getRoleRepository()->createModel()->create([
		    'name' => 'Bedrijf',
		    'slug' => 'bedrijf',
		]);

		$role = Sentinel::getRoleRepository()->createModel()->create([
		    'name' => 'Barcode',
		    'slug' => 'barcode_user'
		]);
		
		$adminRole = Sentinel::findRoleByName('Admin');
		$companyRole = Sentinel::findRoleByName('Bedrijf');

		$adminRole->users()->attach($admin);
		$companyRole->users()->attach($company);
    }
}
