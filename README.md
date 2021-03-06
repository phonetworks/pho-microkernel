<p align="center">
  <img width="375" height="150" src="https://github.com/phonetworks/commons-php/raw/master/.github/cover-smaller.png">
</p>

# pho-microkernel

Enhances Phở Framework with Services (such as persistence) and ACL (access-control-lists).

Existing services are:

* Database: provides persistence for objects created in the framework.
* Events: renders the platform extensible.
* Index: enables advanced search capabilities.
* Logger: dumps important debug info.
* Storage: provides binary storage.

While microkernel provides the interfaces to use for each service, the services are actually implemented by adapters 
that can be found at [pho-adapters](https://github.com/pho-adapters) Github repo.

microkernel [ACL](https://github.com/phonetworks/pho-microkernel/blob/master/src/Pho/Kernel/Acl/README.md)
 is heavily inspired by UNIX.
 
 ## Install

The recommended way to install pho-microkernel is through composer.

```composer require phonetworks/pho-microkernel```

Please note, pho-microkernel works with PHP 7.2+ only.
 
## Tutorial

To get started with creating REST APIs using pho-microkernel, check out https://ideasforfree.org/2020/05/08/how-to-create-a-tinder-clone-in-pho/
 
## License

MIT, see [LICENSE](https://github.com/phonetworks/pho-microkernel/blob/master/LICENSE).
