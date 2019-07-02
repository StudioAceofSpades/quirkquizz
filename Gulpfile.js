
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

        var to = arg.to || conf.default; 

        //Figure out the names of the fonts being generated
        var types = conf.aliases[to];
        var codepoints = {};
        var currentCodePoint = 0xf101;

        for(var i = 0; i < types.length; i++) {
            //Make sure that this already exists in our icons
            var type = types[i];
            var thisConf = conf.definitions[type];

            if(!currentIcons[type]) {
                currentIcons[type] = [];
            }
            
            //Make sure the icon doesn't already exist
            if(currentIcons[type].indexOf(icon) !== -1) {
                log("Icon already exists for " + type); 
            } else {
                currentIcons[type].push(icon);
            }

            //Get the file names
            var startPoint = currentCodePoint;
            var files = [];
            for(var j = 0; j < currentIcons[type].length; j++) {
                var activeIcon = currentIcons[type][j];
                var filePath = iconInput + thisConf.directory + "/" + activeIcon + ".svg"
                if(fs.existsSync(filePath)) {
                    files.push(filePath);

                    if(!codepoints[activeIcon]) {
                        codepoints[activeIcon] = currentCodePoint;
                        currentCodePoint += 1;
                    }
                }
            }

            //Generate the font
            webfontsGenerator({
                files : files,
                css : false,
                fontName : thisConf.prefix + "-" + thisConf.directory + "-" + thisConf.weight,
                dest: iconOutput,
                codepoints : codepoints,
                startCodePoint : startPoint
            }, function(error, results) {
                if(error) {
                    log(error);
                }
            });
        }

        //Generate the SASS file
        var fileData = "";
        for(icon in codepoints) {
            fileData += "'" + icon + "' : \\" + toHex(codepoints[icon]) + ",\n"; 
        }
        fileData = "$available-icons: (\n" + fileData + ");";
        fs.writeFile(iconSass, fileData, function(err) {
            if(err) {
                log(err);
            }
        });

        //Re create the JSON file
        fs.writeFile(activeIcons, JSON.stringify(currentIcons), function(err) {
            if(err) {
                log(err);
            }
        });
        
    } else {
        log("No icon given");
    }
});

gulp.task('default', ['dev', 'watch']);
