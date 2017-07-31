# pho-microkernel

Enhances Pho Framework with Services (such as persistence) and ACL (access-control-lists).

Existing services are:

* Database: provides persistence for objects created in the framework.
* Events: renders the platform extensible.
* Logger: dumps important debug info.
* Storage: provides binary storage.

While microkernel provides the interfaces to use for each service, the services are actually implemented by adapters 
that can be found at [pho-adapters](https://github.com/pho-adapters) Github repo.

microkernel [ACL](https://github.com/phonetworks/pho-microkernel/blob/master/src/Pho/Kernel/Acl/README.md)
 is heavily inspired by UNIX.
