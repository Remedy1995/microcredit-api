php artisan make:model ApplicationTypes
php artisan make:migration create_application_types_table
open the migration table and all columns
view all routes
$ php artisan route:list --path=api
create factory
$ php artisan make:factory LoanFactory
type this in the factory class
    return [
            //
            'loan_amount'=>fake()->sentence(),
             'loan_description'=>fake()->sentence(),
             'loan_repayment_date'=>'12-12-2022',
             'application_status'=>'PENDING'
        ];
        do this in the Database Seeder Table
        LoanApplication::factory(10)->create();

and type php artisan migrate# microcredit-api
