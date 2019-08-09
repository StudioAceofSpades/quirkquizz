
const  webfontsGenerator  =  require('webfonts-generator');
const  fs                 =  require('fs');
const  gulp               =  require('gulp');
const  sass               =  require('gulp-sass');
const  sourcemaps         =  require('gulp-sourcemaps');
const  autoprefixer       =  require('gulp-autoprefixer');
const  concat             =  require('gulp-concat');
const  replace            =  require('gulp-replace');

const  resource_dir       =  "./public_html/wp-content/themes/premise/";
const  logfile            =  "./public_html/sass.log.txt";
const  source_dir         =  "sourcemaps/";
const  input              =  resource_dir +  "sass/**/*.scss";
const  prodOutput         =  resource_dir;
const  devOutput          =  resource_dir +  "devcss/";
const  iconDir            =  resource_dir +  "icons/";
const  iconOutput         =  iconDir +  "fonts/";
const  iconInput          =  iconDir +  "svgs/";
const  iconConf           =  iconDir +  "icon-styles.json";
const  activeIcons        =  iconDir +  "active.json";
const  iconSass           =  resource_dir + "sass/base/_icon-list.scss";

function toHex(number) {
    return (number).toString(16);
}

// fetch command line arguments
const arg = (function(argList) {

  let arg = {}, a, opt, thisOpt, curOpt;
  for (a = 0; a < argList.length; a++) {

    thisOpt = argList[a].trim();
    opt = thisOpt.replace(/^\-+/, '');

    if (opt === thisOpt) {

      // argument value
      if (curOpt) arg[curOpt] = opt;
      curOpt = null;

    }
    else {

      // argument name
      curOpt = opt;
      arg[curOpt] = true;

    }

  }

  return arg;

})(process.argv);

function log(err) {
    fs.writeFile(logfile, err, function() {
        if(process.argv.indexOf("--silent") == -1) {
            console.log(err);
        }
        
    });
}


function rebuildIcons() {
    //Get the raw configuration
    var confRaw = fs.readFileSync(iconConf);
    var iconsRaw = fs.readFileSync(activeIcons);

    //Get our conf, contains directories and names
    if(!confRaw) {
        log("Could not read configuration file at " + iconConf);
        return;
    }
    var conf = JSON.parse(confRaw);

    //Find any icons that are already active
    if(!iconsRaw.length) {
        var currentIcons = {};
    } else {
        var currentIcons = JSON.parse(iconsRaw);
    }
    //Map the icon names to codepoints - the codepoints will be the same across different font weights
    var codepoints = {};

    //Start the code point at the beginning of the Unicode custom section
    var currentCodePoint = 0xf101;
    var startCodePoint = currentCodePoint;

    //Regenerate our codepoints and get the list of files
    var fileLists = {};
    for(activeIcon in currentIcons) {
        //Get the list of types of this icon
        var iconTypes = currentIcons[activeIcon]

        //Gather the SVG file names for all of the icons in our font

        //Increment our codepoint
        codepoints[activeIcon] = currentCodePoint;
        currentCodePoint += 1;


        for(i in iconTypes) {
            var type = iconTypes[i];
            var thisConf = conf.definitions[type];
            if(!(type in fileLists)) {
                fileLists[type] = [];
            }
            //Generate the file name
            var filePath = iconInput + thisConf.directory + "/" + activeIcon + ".svg"
            if(fs.existsSync(filePath)) {
                fileLists[type].push(filePath);
            }
        }

    }

    //Regenerate all fonts, as codepoints may have changed
    for(type in conf.definitions) {
        var thisConf = conf.definitions[type];
        if(type in fileLists) {
            //Generate the font
            webfontsGenerator({
                files : fileLists[type],
                css : false,
                fontName : thisConf.prefix + "-" + thisConf.directory + "-" + thisConf.weight,
                dest: iconOutput,
                codepoints : codepoints,
                startCodePoint : startCodePoint
            }, function(error, results) {
                if(error) {
                    log(error);
                }
            });
        }
    }

    //Generate the SASS file
    var fileData = "";
    for(icon in codepoints) {
        fileData += "'" + icon + "' : \"\\" + toHex(codepoints[icon]) + "\",\n"; 
    }
    fileData = "$available-icons: (\n" + fileData + ");";
    fs.writeFile(iconSass, fileData, function(err) {
        if(err) {
            log(err);
        }
    });

}

//Dev pipeline
var devOptions = {
    errLogToConsole: true,
    outputStyle: 'expanded',
    stdout: false,
    stderr: false,
};
gulp.task('dev', function() {
    return gulp.src(input) //input dir
        .pipe(sourcemaps.init()) //initialize sourcemaps
        .pipe(sass(devOptions).on('error', function(err) {
            log(err);

            //Keep running, even through errors
            this.emit("end");
        })) //run sass
        .pipe(autoprefixer()) //generate vendor prefixes
        .pipe(concat('style.css'))
        .pipe(sourcemaps.write(source_dir)) //generate the sourcemaps inline
        .pipe(gulp.dest(devOutput)); //output CSS
});


var prodOptions = {
    outputStyle: 'compressed'
};
gulp.task('prod', function() {
    return gulp.src(input) //input dir
        .pipe(sass(prodOptions)) //Generate the sass
        .pipe(autoprefixer()) //prefix it
        .pipe(concat('style.css'))
        .pipe(replace(/\.\.\//g, './')) //Fix directory structure
        .pipe(gulp.dest(prodOutput)); //output CSS
});

gulp.task('watch', function() {
    return gulp.watch(input, ['dev'])
        .on('change', function(event) {
            log('File ' + event.path + ' was ' + event.type + ', running tasks...');
        });
});

/** Add a font awesome icon.
This adds both the SASS, allowing the class `fa fa-icon`
to be added, as well as regenerating the font files.
Usage: 
gulp icon-add --icon {{icon}} --to {{default|brands}}
**/
gulp.task('icon-add', function(a) {
    if(arg.icon) {
        //Load our configurations
        var confRaw = fs.readFileSync(iconConf);
        var iconsRaw = fs.readFileSync(activeIcons);
        var icon = arg.icon;

        //Get our conf, contains directories and names
        if(!confRaw) {
            log("Could not read configuration file at " + iconConf);
            return;
        }
        var conf = JSON.parse(confRaw);

        //Find any icons that are already active
        if(!iconsRaw.length) {
            var currentIcons = {};
        } else {
            var currentIcons = JSON.parse(iconsRaw);
        }

        //What font weight / group should the icon be added to
        var to = arg.to || conf.default; 

        //Figure out the names of the fonts being generated
        var types = conf.aliases[to];


        if(!(icon in currentIcons)) {
            currentIcons[icon] = [];
        }

        //Add the icon to the list of icons
        for(index in types) {
            var type = types[index];
            if(currentIcons[icon].indexOf(type) == -1) {
                currentIcons[icon].push(type);
            } else {
                log("Icon already exists for " + type); 
            }
        }

        //Re create the JSON file for existing icons in the font
        fs.writeFile(activeIcons, JSON.stringify(currentIcons), function(err) {
            if(err) {
                log(err);
            } else {
                
                //Regenerate the icons
                rebuildIcons();
            }
        });

        
    } else {
        log("No icon given");
    }
});

gulp.task('default', ['dev', 'watch']);
