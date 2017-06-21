<?php

/*
 * This file is part of the Pho package.
 *
 * (c) Emre Sokullu <emre@phonetworks.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pho\Kernel;

/**
 * This file contains default configuration variables
 * for the kernel. If no configuration specified,  the Kernel
 * will run with these values.
 *
 * @author Emre Sokullu
 */

return array(
      "services" => array( // Services: type defines the adapter to use, and uri the service options.
          "database" => "apcu:", // ["type"=>"apcu", "uri"=> "" ],
          "logger" => "stdout:", // ["type"=>"stdout", "uri"=> "" ],
          "storage" => "filesystem:", // ["type"=>"filesystem", "uri"=> "" ],
          "events" => "local:", // ["type"=>"local", "uri"=> "" ]
      ),
      "root_edge_label" => "AbstractEdge", // The top-level Edge label.  You will not probably not need to change this at any time.
      "root_graph_key" => "root", // The key to store the top-level node.  Do not alter this with running systems.
      "tmp_path" => sys_get_temp_dir(), // Temporary folder to store files. For example uploaded files may go there.
      "root_path" => __DIR__,
      "adapter_path" => __DIR__ . DIRECTORY_SEPARATOR ." Services" . DIRECTORY_SEPARATOR . "Adapters",
      "namespaces" => array(
          "root" => __NAMESPACE__,
          "predicates" => __NAMESPACE__."\\Predicates\\",
          "services" => __NAMESPACE__."\\Services\\",
          "nodes" => __NAMESPACE__."\\Nodes\\",
          "edges" => __NAMESPACE__."\\Edges\\",
      ),
      "log_level" => "WARNING", // INFO
      "database_key_separator" => "/",
      "group_logo_constraints" => array(
          "width" => ["min"=>200, "max"=>600], // in px.
          "height" => ["min"=>400, "max"=>1000], // in px.

          /**
           * @link http://www.php.net/manual/en/function.exif-imagetype.php for image types in PHP
           */
         "format" => [IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF]
      )
    );
