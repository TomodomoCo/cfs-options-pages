# CFS Options Pages

This plugin allows you to create options pages for use with [Custom Field Suite](http://customfieldsuite.com/).

Although it works, and is relatively stable, the code will probably change a fair bit over the coming days/weeks and it's not recommended for use in production unless you're absolutely sure you know what you're doing.

## How?

By default, the plugin will add a single options page, appropriately titled "Options".

To add additional pages, filter `cfs_options_pages`:

```php
function my_custom_options_pages( $pages ) {
	$my_pages = array(
		'Test Options',
		'Another Page',
		'Categories',
	);

	return array_merge( $pages, $my_pages );
}
add_filter( 'cfs_options_pages', 'my_custom_options_pages' );
```

The above code adds three additional options pages.

After the pages are created, you can link them to CFS field groups within CFS's UI. The options pages will show up in CFS's "Posts" selector, in the Placement Rules metabox.

![Choosing an options page in CFS placement rules](https://i.imgur.com/Gzlr221.png)

### Retrieving data

To get data from a specific page or post, Custom Field Suite's `get()` method lets you enter a post/page ID as a second parameter ([documented here](http://customfieldsuite.com/docs/get/)). To get data from an options page, you just need to pass the ID of the desired options page.

You can use our custom method&mdash;`CFS()->options->page( $title )`&mdash;to retrieve an options page ID. You can use it directly within your `CFS()->get()` call. Here's how that looks:

```php
echo CFS()->get( 'my_field', CFS()->options->page( 'My Options' ) );
```

If you don't pass an argument to the `page()` method, we'll retrieve the ID of the default options page (the page we create by default, titled "Options").

```php
echo CFS()->get( 'my_field', CFS()->options->page() );
```

If an options page matching your title is not found, we return `FALSE`. If that happens, [CFS will use the current post](http://customfieldsuite.com/docs/get/).

## Caveats

If you add a custom options page using the `cfs_options_page` filter, and later remove it, that page will **not** be deleted from your database. We're working on ideas to improve this behavior, but for the moment avoid adding extraneous options pages.

If you migrate your options pages and field groups between development/staging/production environments, be aware that your options page IDs may change. This would cause your field groups to become decoupled from your options pages.

## License

**Copyright (c) 2014 [Van Patten Media Inc.](https://www.vanpattenmedia.com/) All rights reserved.**

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

*   Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
*   Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
*   Neither the name of the organization nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

