## Build and run the environment
```
> ./vendor/bin/sail up
```
**Access or call the APIs**
```
http://localhost:8080/api/
```

## Create a new module or API endpoint
```
> ./vendor/bin/sail bash
```

1. Create repository pattern scaffolding
```
> php artisan make:repository Sample --model=Sample --table=samples
```
2. Update the created or related migration file.
3. Update the created or related model file.
4. Update the created or related policy file.
5. Update the created or related factory file.
6. Create seeder, then add it in the DatabaseSeeder file.
7. Run the migration and seeder.
```
> php artisan migrate
> php artisan db:seed
```
8. Register the created or related policy in the `App\\Providers\\RepositoryServiceProvider.php`.
9. Add api resource routes in api.php.
```
Route::apiResource('samples', SampleController::class);
```
10. Update the controller to add business logic.
11. Create resource, for the response formatting, then update the said file accordingly.
```
> php artisan make:resource SampleResource
```
12. Create a from request instance and set the authorization and field validations.
```
> php artisan make:request StoreSampleRequest
```

## Testing
1. Create unit or feature test
```
> php artisan make:test SampleApiTest // for feature tests
> php artisan make:test SampleApiTest --unit // for unit tests
```
2. Execute the whole test or specific test.
```
> php artisan test // test all
> php artisan test --filter=SampleApiTest
```