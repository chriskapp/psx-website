PSX Website
===

## About

This is the source code of the PSX website (http://phpsx.org). Basically the
website conists of some pages and a simple blog system. The file blog.xml is an
atom feed where every news article gets published. The UpdateBlogCommand command
fetches all entries from this file and inserts them into an sqlite database. 
There is also an command to fetch the latest releases from GitHub. The latest 
release gets then displayed on the download page.

Since PSX is an framework to create RESTful APIs every page has also different
media type representation formats. If you request i.e. an page with an Accept 
header "application/json" the JSON representation of the page gets returned. By 
default there are several media types supported. Hint try to request the front 
page with an Accept "text/plain" header.

