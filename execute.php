<?php
$NAME = $argv[1];
$PORT = $argv[2];

shell_exec("mkdir /home/yoggi/shalomi/container/$NAME");

$yaml = array(
  "shalomi" => array(
    "image" => "cescgie/shalomiapp:latest",
    "volumes" => array(
      "./uploads:/app/uploads"
    ),
    "environment" => array(
      "PORT" => "$PORT",
      "ROOT_URL" => "http://localhost:$PORT",
      "MONGO_URL" => "mongodb://mongo:27017/$NAME",
      "MAIL_URL" => "smtp://smtp.email",
    ),
    "links" => array(
       "mongo:mongo"
     ),
    "ports" => array(
       "$PORT:$PORT"
     ),
  ),
  "mongo" => array(
    "image" => "mongo:3.2",
    "volumes" => array(
      "./data/db:/data/db",
      "./data/dump:/dump",
    ),
  ),
);


$docker_compose = yaml_emit($yaml);
$h = fopen("/home/yoggi/shalomi/container/$NAME/docker-compose.yml", "w");
fwrite($h, print_r($docker_compose, TRUE));
fclose($h);

echo "Starting $NAME...";
shell_exec("cd /home/yoggi/shalomi/container/$NAME/; docker-compose up -d");
?>
