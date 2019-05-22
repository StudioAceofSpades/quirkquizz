var fs           = require('fs');
var gulp         = require('gulp');
var sass         = require('gulp-sass');
var sourcemaps   = require('gulp-sourcemaps');
var autoprefixer = require('gulp-autoprefixer');
var concat       = require('gulp-concat');
var replace      = require('gulp-replace');

var resource_dir = "./public_html/wp-content/themes/premise/";
var logfile      = "./public_html/sass.log.txt";
var source_dir   = "sourcemaps/";
var input        = resource_dir + "sass/**/*.scss";
var prodOutput   = resource_dir;
var devOutput    = resource_dir + "devcss/";

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
        var sass_file = resource_dir + "sass/base/_icon-list.scss";
        var sass_entry = "'" + arg.icon + "' : $fa-var-" + arg.icon + ",";
        fs.readFile(sass_file, {encoding: 'utf-8'}, function(err, data) {
            if(err) {
                log(err);
                return;
            } 
            var r = new RegExp("['\"]" + arg.icon + "['\"]");
            if(data.search(r) == -1) {
                data = data.replace(");", "    " + sass_entry + "\n);");
                fs.writeFile(sass_file, data, function(err) {
                    if(err) {
                        log(err);
                    } else {
                        log("Added icon.");
                    }
                });

            } else {
                log("Icon already present");
            }
        });
    } else {
        log("No icon given");
    }
});

gulp.task('default', ['dev', 'watch']);
