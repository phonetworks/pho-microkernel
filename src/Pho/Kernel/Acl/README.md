# Acl

Acl stands for "access-control-lists". Pho handles access to nodes and graphs similarly to how UNIX handles access to files and folders, hence the name.

With Pho:

* a node is the UNIX equivalent of a file.
* a graph is the UNIX equivalent of a directory.

In terms of privileges;

* read remains the same for both.
* write reamins the same for both.
* UNIX' execute is Pho's subscribe.
* In addition, Pho introduces the "manage" privilege.

Due to this additional privilege (e.g. "manage"), Pho uses a hexadecimal system to manage privileges, in contrast to UNIX' octal.

Plus, in terms of privilege groups, Pho introduces an additional one, called "subscribers":

* UNIX' u (users) remain the same. The u group is the owner of the object (or the actor itself, if it's an actor).
* s (subscriber) is a new privilege group. It consists of all the subscribers of this node. The subscribers include the head nodes of any edge that extends the Subscribe edge. 
* g (graph) is same as UNIX' g (group). It is the group of actors that belong to the same context with the "u" of this node.
* o (others) is same as UNIX' o. It is "others", meaning actors that belong to graphs not included by the context that the "u" belongs to.

Additionally, one can set up fine-grained privileges per actor and graph, using Pho's advanced access control lists, which is again, inspired by UNIX' access control lists, but slightly different.

Again, similarly to UNIX, Pho also has "sticky-bit" which ensures the privileges of an entity (or object) can only be changed by its owner, and not by the admins.

One can change a node's privileges with this simple command:

```php
$node->acl()->chmod(0x1e741);
```

where the parameter is (from left-to-right):

* 0x: is PHP's hexadecimal declaration. So it must be there.
* 1: is the sticky bit. Similar to UNIX, it may be 0 or 1.
* e: is the "u". This gives the u "manage" privilege, in addition to "read", "write" and "subscribe". So it's 1(subscribe) + 2(write) + 4(read) + 8(manage) = 15("e" in hexadecimal).
* 7: is the "s". Subscribers can write to, read and subscribe to this node, but not manage it.
* 4: is the "g". People in the same graph with this node.
* 1: is the "o" -- outsiders.

So for example, if this $node was a group (like a Facebook Group), this means:

* Only the group owner can edit or assign new privileges.
* The group managers can do anything, except, see above.
* The members (subscribers) of this group can post (write) and read anything.
* The people who are part of this network can read and subscribe the contents of this group, which means it's a read-only public one.
* The outsiders can only see the description of ../. 

## Permissions Table

(work-in-progress)

|           | Admin            | Read               | Write          | Execute (Subscribe)
| --------- | ---------------- | ------------------ | -------------- | --------------------
| Actor     | Manage profile   | See full profile.  | Send message   | Follow/become friends (or if friends already, react)
| Object    | Manage reactions | Read               | Edit           | Subscribe/*react*
| Graph     | Moderate/profile | Read contents      | Post content   | Subscribe
