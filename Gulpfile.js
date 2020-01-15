
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

gulp.task('default', ['dev', 'watch']);
