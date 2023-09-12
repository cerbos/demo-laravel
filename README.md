# demo-laravel

This project demonstrates how to utilize [Cerbos](https://cerbos.dev) with 
[Cerbos Laravel SDK](https://github.com/cerbos/cerbos-sdk-laravel).

## API

### `POST /api/register`

Register a user
```json
{
    "email": "someone@someone.com",
    "password": "123",
    "name": "Someone",
    "roles": "USER,MANAGER",
    "region": "EU",
    "department": "IT"
}
```

### `POST /api/login`

Login with e-mail and password
```json
{
    "email": "someone@someone.com",
    "password": "123"
}
```

### `POST /api/logout`

Logout

### `GET /api/expenses`

List all expenses the logged-in user authorized to view

### `GET /api/expenses/{id}`

Get expense by ID if logged-in user is authorized to view

### `POST /api/expenses`

Create new expense
```json
{
    "amount": 500,
    "region": "EMEA",
    "vendor": "ACME Inc."
}
```

### `DELETE /api/expenses/{id}`

Delete the expense by ID if logged-in user is authorized to delete

### `PATCH /api/expenses/{id}`

Update the expense by ID if logged-in user is authorized to update

```json
{
    "amount": 500,
    "region": "EMEA",
    "vendor": "ACME Inc."
}
```

### `POST /api/expenses/{id}/approve`

Approve the expense by ID if logged-in user is authorized to approve

### `POST /api/expenses/{id}/reject`

Reject the expense by ID if logged-in user is authorized to reject

## Seeds

Running `php artisan db:seed` seeds the database with the following rows.

### User Seeds (`database/seeders/UserSeeder.php`)

| ID | Name   | Mail             | Password | Roles         | Department | Region |
|----|--------|------------------|----------|---------------|------------|--------|
| 1  | Sally  | sally@sally.co   | 123      | USER          | SALES      | EMEA   |
| 2  | Ian    | ian@ian.co       | 123      | ADMIN         | IT         | -      |
| 3  | Frank  | frank@frank.co   | 123      | USER          | FINANCE    | EMEA   |
| 4  | Derek  | derek@derek.co   | 123      | USER, MANAGER | FINANCE    | EMEA   |
| 5  | Simon  | simon@simon.co   | 123      | USER, MANAGER | SALES      | NA     |
| 6  | Mark   | mark@mark.co     | 123      | USER, MANAGER | SALES      | EMEA   |
| 7  | Sydney | sydney@syndey.co | 123      | USER          | SALES      | NA     |

### Expense Seeds (`database/seeders/ExpenseSeeder.php`)

| ID | Vendor          | Region | Owner ID | Approved By ID | Amount | Status   |
|----|-----------------|--------|----------|----------------|--------|----------|
| 1  | Flux Water Gear | EMEA   | 1        | -              | 500    | OPEN     |
| 2  | Vortex Solar    | EMEA   | 1        | 3              | 2500   | APPROVED |
| 3  | Global Airlines | EMEA   | 1        | -              | 12000  | OPEN     |
| 4  | Vortex Solar    | EMEA   | 3        | -              | 2421   | OPEN     |
| 5  | Vortex Solar    | EMEA   | 1        | 3              | 2500   | REJECTED |
