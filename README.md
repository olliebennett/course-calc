# CourseCalc.com

__Calculate your current degree grade in a snap! Visit [CourseCalc.com](http://coursecalc.com/)!__

## To-Do List

This is a list of planned improvements, (loosely) sorted in descending order of priority.

### Planning Implementation

- bulk uploading content by pasting a table/csv/text of your data (allowing users to copy/paste from university intranets, spreadsheets etc.)

- background text in form inputs [like this](https://github.com/dcneiner/In-Field-Labels-jQuery-Plugin).

- graph of year-on-year results?

- redesign `save.php` to use MySQLi (which `load.php` already uses)

- allow deleting any row/module, other than the final one

- Better UI for adding/removing green/red add/remove buttons

- Remove superfluous "totalling x credits" etc. text, which degrades UX.

- Implement JavaScript (interactive) graphs

- Perhaps use [backbone.js](http://backbonejs.org/): collection=year, model=module, each row = view (each bound to model)

- Enable 'undeleting' of a single row

### Considering Implementation

- allow reordering rows/modules

- [guiders](https://github.com/jeff-optimizely/Guiders-JS) for first-time users.

- sliderbar to set percentage weightings for the years, [like this](http://www.frequency-decoder.com/demo/slider-v2/).

- avoid saving a new copy of data (and hence creating a new shortcode) if nothing has changed.

- allow creating user to modify course details, OR use versioning system like jsFiddle does.

- move calculation to client-side, rather than using PHP

## Contributing

If you've got any ideas for things to implement, get in touch through GitHub or via [OllieBennett.co.uk](http://olliebennett.co.uk).

If possible, fork the project, add the feature/bugfix yourself, and throw me a pull request!

## Changelog

- __2012-10__ Uploaded to GitHub!

- __2012-06__ Finished to an acceptable level and hosted at [CourseCalc.com](http://coursecalc.com/).

- __2012-05__ Added (rudimentary) styling!

- __2012-04__ Database (i.e. permanent storage) implemented.

- __2010-12__ Wireframe and simple custom-javascript functionality, but no calculations performed.
