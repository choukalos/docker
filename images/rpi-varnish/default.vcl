vcl 4.0;

import std;

backend default {
  .host = "127.0.0.1";
  .port = "8080";
  .first_byte_timeout     = 300s;
  .connect_timeout        = 5s;
  .between_bytes_timeout  = 5s;
}
