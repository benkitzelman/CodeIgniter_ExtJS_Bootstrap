<?php

//
// Helpers are generally included in the config/autoload file. They can be accessed by a controller, or view.
// To access a helper in a layout, it must be explicitly loaded in the layout of interest
//

//
// Finders are essentially short cut functions. If a custom Finder class (models/finder)
// extending Doctrine_Table exists, that will be returned instead of the generic Doctrine Table.
//
function active_survey_finder(){return Doctrine::getTable("ActiveSurvey");}
