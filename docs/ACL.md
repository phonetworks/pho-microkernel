In order to access the ACL of a particle; use:

```php
$obj->acl();
```

Then, you can set up privileges with:

```php
// 1. UNIX style.
$obj->acl()->chmod(0x07777); // gives RWX privileges to all users.

// 2. Fine-grained
$obj->acl->set("u:".$some_actor->id()->toString().":", "mrwx"); // gives RWX features, plus moderation to this particular user.
$obj->acl->set("g:".$some_graph->id()->toString().":", "mrwx"); // gives RWX features, plus moderation to this particular graph's members.
```

Once the privileges is set up as desired, you can then check them out with:

```php
$obj->acl()->writeable($actor); // checks if the actor (in given context (which can be found by $actor->pwd()) can write this object
$obj->acl()->readable($actor); // checks if the object is readable by the given actor.
$obj->acl()->executable($actor); // checks if the objecy is executable by the given actor.
$obj->acl()->manageable($actor); // manageable is a Pho concept. More than W, it allows moderation of that particular object.
```
