Running application
===================

`docker-compose -f docker-compose.yml -f docker-compose.dev.yml up`

Api doc will be available at http://localhost:8080/app_dev.php/doc

There is a login button next to each endpoint (in sandbox),
you can login using following data:

- admin:admin
- user:user


Tasks
=====

1. Edit and create blog post
Add new endpoints, one for creating blog post and one for editing.
Title, content and tags should be editable.
Tags will be send to api endpoint in form of simple string, each tag separated by semicolon.
Security:
Available only for admin

2. Allow to publish blog post do different services (targets)
Provide solution for publish post to e.g. facebook, twitter.
Do not implement integration with those services, just mock this behaviour.
Url will look like this provided in `BlogPostController` /blog-post/{post}/{target}
If such target does not exists (e.g. someone will send `google` as target) throw an TargetNotExistException
and implement listener for it to return some user friendly response in such case.
Hint:
Use tagged services.
Security:
Action available only for admin.

3. Secure BlogPostController::listPostsAction, make it available for admin and user.




