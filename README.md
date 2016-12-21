# SkelPHP Component

*NOTE: The Skel framework is an __experimental__ web applications framework that I've created as an exercise in various systems design concepts. While I do intend to use it regularly on personal projects, it was not necessarily intended to be a "production" framework, since I don't ever plan on providing extensive technical support (though I do plan on providing extensive documentation). It should be considered a thought experiment and it should be used at your own risk. Read more about its conceptual foundations at [my website](https://colors.kaelshipman.me/about/this-website).*

Skel Component is one of the central elements of the Skel idea. A component is simply a collection of data elements that can be rendered to string. It was created to be the fundamental return type of an application controller. If the controller returns the *data* in a structured format, rather than a *string* rendered from that data, then the controller can be used as a library in other applications because the other application can choose to render the Component differently than the original application might have.

For example, consider the following component and template:

```json
// "Person" Component
{
  "firstName" : "José Arcadio",
  "lastName" : "Buendía"
}
```

```html
<!-- "Person" Template -->
<div class="person">
  <img src="/imgs/##firstName##-##lastName##.jpg">
  <p>##firstName## ##lastName##</p>
</div>
```

If my controller returned these two items, I could choose to either render them as is or further modify the data, or even use the data for further queries and program manipulation. However, if the controller simply returned the rendered string, I wouldn't have many options. This is why it is encouraged to pass Components around (rather than strings or other raw data) until the very end, at which point all components can be rendered at once.

The only thing that's not very straightforward about this package is the relationship of Component to ComponentCollection and why ComponentCollection is even necessary. In short, a ComponentCollection is an array of Components, and when its `__toString` method is called, it renders all of its children into a string, separated by "\n". More on this later....

Each Skel Component has an optional Template associated with it. This package provides two Template implemenations -- StringTemplate, which uses simple string substition to render, and PowerTemplate which allows your template to be included in the program execution. Note that PowerTemplate automatically includes the rendering Component as the $component variable, so if you need to access data from subcomponents, you can do so via `$component['your-subcomponent-here']`.

## Usage

Eventually, this package is intended to be loaded as a composer package. For now, though, because this is still in very active development, I currently use it via a git submodule:

```bash
cd ~/my-website
git submodule add git@github.com:kael-shipman/skelphp-traits.git app/dev-src/skelphp/component
```

This allows me to develop it together with the website I'm building with it. For more on the (somewhat awkward and complex) concept of git submodules, see [this page](https://git-scm.com/book/en/v2/Git-Tools-Submodules).

