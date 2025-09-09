## Requirements
### Backend
- PHP 8.3.6
- Composer 2.7.1
### Frontend
- NodeJs 24.7.0
- PNPM 10.15.1

## Environment setup
1. Create `.env` file, copy the content of `.env.example` and update accordingly.
2. For the frontend, go to the `frontend` directory and do the following.
```
> touch .env

# create symlink to the main .env
> ln -s ../.env .env
```
2. Build and run the application
```
# In the root directory, for the Laravel app
> ./vendor/bin/sail up

# Go to frontend directory, for the NuxtJs app
> pnpm install
> pnpm run dev
```
3. Access the apps
- API: http://localhost:8080
- Web App: http://localhost:3000

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
10. Execute the whole test or specific test.
```
> php artisan test // test all
> php artisan test --filter=SampleApiTest
```

## Linting
- Backend, in the root directory
```
> ./vendor/bin/pint
```
- Frontend, got to the `frontend` directory
```
> pnpm run lint
```