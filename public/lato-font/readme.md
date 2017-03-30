# Lato font

[![Bower version](https://badge.fury.io/bo/lato-font.svg)](http://badge.fury.io/bo/lato-font)
[![npm version](https://badge.fury.io/js/lato-font.svg)](http://badge.fury.io/js/lato-font)

This is the latest official `2.015` web-version of the OpenSource [Lato font][lato]
suitable to be used with [Bower][bower] and [npm][npm].


## Features

- latest official web-optimized version of the *Lato* font
- supports all unicode ranges (i.e. languages), variants and styles provided by *Lato* font
- provides both *CSS* and *SCSS* (*SASS/Compass*) integration options
- installs with *Bower* and *npm*
- individual fonts can be added to a project using [*SCSS* integration][scss-api]
- all fonts can be added to a project just by including provided CSS-file
- library is extremely customizable and provides very convenient [*SCSS* API][scss-api]


## Installation

### Install library with *Bower*

`bower install --save lato-font`

### Install library with *npm*

`npm i --save lato-font`


## Usage

Either [link library directly via *CSS*][css-integration] or use provided [SCSS API][scss-api] in your build process.

By using [*SCSS* API][scss-api] you can easily add individual fonts to your project!


### Hint

*Medium* versions of *Lato* font (both regular and italic) are available under the `Lato Medium` name instead of just `Lato`.
This is required because *Medium* and *Normal* variants are using the same weight of `400`.
*CSS* only supports values divisible by `100` for the `font-weight` property.


### CSS integration

Just link provided CSS file to your page:

`<link rel="stylesheet" href="/bower_components/lato-font/css/lato-font.css">`

Then use provided font-faces it in your CSS:

```css
h1 {
  font-family: Lato;
  font-weight: 900;
  font-style: normal;
}

p.quote {
  font-family: "Lato Medium";
  font-weight: 400;
  font-style: italic;
}
```


### SCSS API

The best way to use this font library is by integration with *SCSS* (*SASS/Compass*).

Consider this example:

```scss

@import 'public-api';

$lato-font-path: '/bower_component/lato-font/fonts';

@include lato-include-font('black');
@include lato-include-font('medium');

h1 {
	@include lato-font('black');
}

p.quote {
	@include lato-font('medium', italic);
}

```

You will get the following result in *CSS*:

```css

/* Lato (black, regular) */
@font-face {
  font-family: Lato;
  src: url("/bower_component/lato-font/fonts/lato-black/lato-black.eot");
  src: url("/bower_component/lato-font/fonts/lato-black/lato-black.eot?#iefix") format("embedded-opentype"), url("/bower_component/lato-font/fonts/lato-black/lato-black.woff") format("woff"), url("/bower_component/lato-font/fonts/lato-black/lato-black.ttf") format("truetype");
  font-weight: 900;
  font-style: normal;
}
/* Lato (black, italic) */
@font-face {
  font-family: Lato;
  src: url("/bower_component/lato-font/fonts/lato-black-italic/lato-black-italic.eot");
  src: url("/bower_component/lato-font/fonts/lato-black-italic/lato-black-italic.eot?#iefix") format("embedded-opentype"), url("/bower_component/lato-font/fonts/lato-black-italic/lato-black-italic.woff") format("woff"), url("/bower_component/lato-font/fonts/lato-black-italic/lato-black-italic.ttf") format("truetype");
  font-weight: 900;
  font-style: italic;
}
/* Lato (medium, regular) */
@font-face {
  font-family: "Lato Medium";
  src: url("/bower_component/lato-font/fonts/lato-medium/lato-medium.eot");
  src: url("/bower_component/lato-font/fonts/lato-medium/lato-medium.eot?#iefix") format("embedded-opentype"), url("/bower_component/lato-font/fonts/lato-medium/lato-medium.woff") format("woff"), url("/bower_component/lato-font/fonts/lato-medium/lato-medium.ttf") format("truetype");
  font-weight: 400;
  font-style: normal;
}
/* Lato (medium, italic) */
@font-face {
  font-family: "Lato Medium";
  src: url("/bower_component/lato-font/fonts/lato-medium-italic/lato-medium-italic.eot");
  src: url("/bower_component/lato-font/fonts/lato-medium-italic/lato-medium-italic.eot?#iefix") format("embedded-opentype"), url("/bower_component/lato-font/fonts/lato-medium-italic/lato-medium-italic.woff") format("woff"), url("/bower_component/lato-font/fonts/lato-medium-italic/lato-medium-italic.ttf") format("truetype");
  font-weight: 400;
  font-style: italic;
}
/* line 8, ../scss/api-example.scss */
h1 {
  /* Lato (black, normal) */
  font-family: Lato;
  font-weight: 900;
  font-style: normal;
}

/* line 12, ../scss/api-example.scss */
p.quote {
  /* Lato (medium, italic) */
  font-family: "Lato Medium";
  font-weight: 400;
  font-style: italic;
}

```

You can see the list of available mixins in `/scss/public/_mixins.scss`.

List of available variants is in `/scss/internal/_variables.scss`.

You can override font path directory using `$lato-font-path` variable.


## Feedback

If you have found a bug or have another issue with the library - please [create an issue][new-issue] in this GitHub repository.

If you have a question - file it with [StackOverflow][so-ask] and send me a
link to [s.fomin@betsol.ru][email]. I will be glad to help.
Also, please create a [plunk][plunker] to demonstrate the issue, if appropriate.

Have any ideas or propositions? Feel free to contact me by [E-Mail address][email].

Cheers!

---


## Licenses

### Library

    The MIT License (MIT)
    
    Copyright (c) 2014 Slava Fomin II
    
    Permission is hereby granted, free of charge, to any person obtaining a copy
    of this software and associated documentation files (the "Software"), to deal
    in the Software without restriction, including without limitation the rights
    to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
    copies of the Software, and to permit persons to whom the Software is
    furnished to do so, subject to the following conditions:
    
    The above copyright notice and this permission notice shall be included in
    all copies or substantial portions of the Software.
    
    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
    IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
    AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
    LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
    OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
    THE SOFTWARE.

### Font

    -----------------------------------------------------------
    SIL OPEN FONT LICENSE Version 1.1 - 26 February 2007
    -----------------------------------------------------------
    
    PREAMBLE
    The goals of the Open Font License (OFL) are to stimulate worldwide
    development of collaborative font projects, to support the font creation
    efforts of academic and linguistic communities, and to provide a free and
    open framework in which fonts may be shared and improved in partnership
    with others.
    
    The OFL allows the licensed fonts to be used, studied, modified and
    redistributed freely as long as they are not sold by themselves. The
    fonts, including any derivative works, can be bundled, embedded, 
    redistributed and/or sold with any software provided that any reserved
    names are not used by derivative works. The fonts and derivatives,
    however, cannot be released under any other type of license. The
    requirement for fonts to remain under this license does not apply
    to any document created using the fonts or their derivatives.
    
    DEFINITIONS
    "Font Software" refers to the set of files released by the Copyright
    Holder(s) under this license and clearly marked as such. This may
    include source files, build scripts and documentation.
    
    "Reserved Font Name" refers to any names specified as such after the
    copyright statement(s).
    
    "Original Version" refers to the collection of Font Software components as
    distributed by the Copyright Holder(s).
    
    "Modified Version" refers to any derivative made by adding to, deleting,
    or substituting -- in part or in whole -- any of the components of the
    Original Version, by changing formats or by porting the Font Software to a
    new environment.
    
    "Author" refers to any designer, engineer, programmer, technical
    writer or other person who contributed to the Font Software.
    
    PERMISSION & CONDITIONS
    Permission is hereby granted, free of charge, to any person obtaining
    a copy of the Font Software, to use, study, copy, merge, embed, modify,
    redistribute, and sell modified and unmodified copies of the Font
    Software, subject to the following conditions:
    
    1) Neither the Font Software nor any of its individual components,
    in Original or Modified Versions, may be sold by itself.
    
    2) Original or Modified Versions of the Font Software may be bundled,
    redistributed and/or sold with any software, provided that each copy
    contains the above copyright notice and this license. These can be
    included either as stand-alone text files, human-readable headers or
    in the appropriate machine-readable metadata fields within text or
    binary files as long as those fields can be easily viewed by the user.
    
    3) No Modified Version of the Font Software may use the Reserved Font
    Name(s) unless explicit written permission is granted by the corresponding
    Copyright Holder. This restriction only applies to the primary font name as
    presented to the users.
    
    4) The name(s) of the Copyright Holder(s) or the Author(s) of the Font
    Software shall not be used to promote, endorse or advertise any
    Modified Version, except to acknowledge the contribution(s) of the
    Copyright Holder(s) and the Author(s) or with their explicit written
    permission.
    
    5) The Font Software, modified or unmodified, in part or in whole,
    must be distributed entirely under this license, and must not be
    distributed under any other license. The requirement for fonts to
    remain under this license does not apply to any document created
    using the Font Software.
    
    TERMINATION
    This license becomes null and void if any of the above conditions are
    not met.
    
    DISCLAIMER
    THE FONT SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
    EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO ANY WARRANTIES OF
    MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT
    OF COPYRIGHT, PATENT, TRADEMARK, OR OTHER RIGHT. IN NO EVENT SHALL THE
    COPYRIGHT HOLDER BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
    INCLUDING ANY GENERAL, SPECIAL, INDIRECT, INCIDENTAL, OR CONSEQUENTIAL
    DAMAGES, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
    FROM, OUT OF THE USE OR INABILITY TO USE THE FONT SOFTWARE OR FROM
    OTHER DEALINGS IN THE FONT SOFTWARE.

  [so-ask]:    http://stackoverflow.com/questions/ask
  [email]:     mailto:s.fomin@betsol.ru
  [plunker]:   http://plnkr.co/
  [new-issue]: https://github.com/betsol/lato-font/issues/new
  
  [lato]: http://www.latofonts.com/
  [bower]: http://bower.io/
  [npm]: https://www.npmjs.com/
  [scss-api]: #scss-api
  [css-integration]: #css-integration
