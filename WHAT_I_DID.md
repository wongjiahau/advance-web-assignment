## Getting driver error?

```
sudo apt-get install php7.2-mysql
```

Also, try to restart the server.
```
sudo service apache2 restart
```


## How to start the server?

```
php artisan serve
```

## What command is typed?

```sh
# Create migrations definition
php artisan make:migration create_authors_book_table --create=authors_book
php artisan make:migration create_books_table        --create=books
php artisan make:migration create_publishers_table   --create=publishers
php artisan make:migration create_authors_table     --create=authors

php artisan make:migration create_groups_table       --create=groups
php artisan make:migration create_group_user_table   --create=group_user
php artisan make:migration create_messages_table     --create=messages

# Run the migration
php artisan migrate

# Create model
php artisan make:model Book
php artisan make:model Publisher
php artisan make:model Author

php artisan make:model Group
php artisan make:model Message

# Create controllers
php artisan make:controller AuthorController
php artisan make:controller BookController
php artisan make:controller PublisherController

php artisan make:controller UserController
php artisan make:controller GroupController
php artisan make:controller MessageController

# Create API resources
php artisan make:resource AuthorResource
php artisan make:resource PublisherResource
php artisan make:resource BookResource

php artisan make:resource UserResource
php artisan make:resource GroupResource
php artisan make:resource MessageResource

# Create API resource collections
php artisan make:resource AuthorCollection
php artisan make:resource PublisherCollection
php artisan make:resource BookCollection

php artisan make:resource UserCollection
php artisan make:resource GroupCollection
php artisan make:resource MessageCollection

# Install bouncer
composer require silber/bouncer v1.0.0-rc.3
php artisan vendor:publish --tag="bouncer.migrations"
php artisan vendor:publish --tag="bouncer.migrations"
php artisan migrate
php artisan make:command InitBouncer
php artisan InitBouncer
php artisan create:role
```

## How to show migration sql?

```
php artisan migrate --pretend
```

## How to remigrate?

```
mysql -uroot -p
drop database laravel_project;
create database laravel_project;
php artisan migrate
```

## How to try the API?

Use HTTPie (download from Internet).

```sh
# For users
http GET    http://localhost:8000/api/users
http GET    http://localhost:8000/api/users/1
echo '{"name": "User1", "email": "User1@gmail.com", "password": "12345678", "password_confirm": "12345678"}' | http POST   http://localhost:8000/api/users
echo '{"name": "User2", "email": "User2@gmail.com", "password": "12345678", "password_confirm": "12345678"}' | http PUT http://localhost:8000/api/users/1
http DELETE http://localhost:8000/api/users/1 

# For groups
http GET    http://localhost:8000/api/groups
http GET    http://localhost:8000/api/groups/1
echo '{"name": "Group1", "creator": "1"}' | http POST   http://localhost:8000/api/groups
echo '{"name": "newLee"}' | http PUT http://localhost:8000/api/groups/1
http DELETE http://localhost:8000/api/groups/1 

# For auth
## Register user
echo '{"name": "bar", "email": "bar@gmail.com", "password": "12345678", "password_confirm": "12345678"}' | http POST http://localhost:8000/api/auth/register

## Login user
echo '{"email": "bar@gmail.com", "password": "12345678"}' | http POST http://localhost:8000/api/auth/login

```

```
http://localhost:8000/api/authors
```

