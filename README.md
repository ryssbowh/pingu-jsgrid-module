# JsGrid Module

## TODO
- [ ] test thoroughly all fields
- [ ] Make model fields lazyload them

## v1.1.3
- added checkbox field
- renamed Contracts/Traits
- added doc

## v1.1.2
- renamed api in ajax

## v1.1
- Refactored controllers to make it more reusable
- integration to route slugs routes in api

## v1.0.5 wrote readme

### JsGrid

This module is used to make a model list page to be rendered as a [Js grid](http://js-grid.com/) list.

It provides with a controller to list a model, fields to define fields as jsgrid accepts them and js assets.

To make a model JsGridable it'll need to implement `JsGridableContract` and use the `JsGridable` trait. this trait defines `jsGridFields()` that will need to be overriden to add fields to the view. each field in this array must have a type, which is one of the Field classname.

You can't use jsGrid if your model is not Formable, therefore a model that implements `JsGridableContract` automatically implements `FormableContract` and must therefore use the traits associated with that contract.

JsGrid is ajax driven and use the api framework as defined in Core, therefore a model that implements `JsGridableModel` automatically implements `AjaxableModel` and must therefore use the traits associated with that contract.

### Assets
If you can't find in the Resources/assets/js/components/fields folder the field you have defined, it means it uses the default js code that jsgrid provides.

If you write a new type of field, you will have to write the js associated to it. If you define a field `TreeView`, you will have to write a js file in that folder called `treeview.js` that defines how this field is rendered, filtered on etc.

Jsgrid.js loads the jsgrid library. The css will load the default css provided by the library.

### Fields
If your model define `JsGridFields()`, it must have one of those fields classname as type. Their name will be passed on the js driving jsgrid.

any jsgrid options can be overriden in this array, for each field.

### Config
Defines default jsgrid field options.