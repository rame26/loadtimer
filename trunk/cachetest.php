<?php
/*
Copyright 2011 Google Inc.

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

     http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.

See the source code here:
     http://code.google.com/p/loadtimer/
*/

/*
  A simple test to see if the user has cleared the cache between runs.
  This file is downloaded at the start of each run.
  It takes N seconds to download (using sleep()).
  On the client if the file's onload event fires in less than N seconds,
  we know it was read from cache and the user didn't clear their cache.
  ISSUES:
    - If the user clears their cache but this file is stored on their
	  proxy the tool will incorrectly determine they did NOT clear their cache.
	- If the user clears their cache and then loads some of the URLs
	  directly (outside of this tool) this tool will think the cache 
	  is clear when it actuality it's full for the URL(s) being tested.
*/
header("Content-Type: text/javascript");
header("Cache-Control: public,max-age=31536000");

sleep(3); // keep this time value in sync with index.php

echo "var cacheTestTime = " . time() . ";\n";
?>
