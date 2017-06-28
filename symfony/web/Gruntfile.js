module.exports = function(grunt) {
    
    require('time-grunt')(grunt);
    require('jit-grunt')(grunt);

    grunt.initConfig({

        pkg: grunt.file.readJSON('package.json'),

        sass: {
            options: {
                sourceMap: false,
                outputStyle: 'nested' // nested, compressed
            },
            dist: {
                files: {
                    'css/style.css': 'src/scss/style.scss',
                    'css/print.css': 'src/scss/print.scss'
                }
            }
        },
        postcss: {
            options: {
                map: false, // inline sourcemaps

                // or
                // map: {
                //     inline: false, // save all sourcemaps as separate files...
                //     annotation: 'dist/css/maps/' // ...to the specified directory
                // },

                processors: [
                    //require('pixrem')(), // add fallbacks for rem units
                    require('autoprefixer')({browsers: 'last 3 versions'}), // add vendor prefixes
                    require('cssnano')() // minify the result
                ]
            },
            dist: {
                src: 'css/*.css'
            }
        },

        include_file: {
            default_options: {
                cwd: 'src/js/',
                src: ['scripts.js'],
                dest: 'js/'
            }
        },

        uglify: {
            app: {
                files: {
                    'js/scripts.min.js': [
                        'js/scripts.js'
                    ]
                }
            }
        },

        watch: {
            options: {
                'spawn': false
            },
            iconfont: {
                files: 'src/icons/*.svg',
                tasks: ['webfont', 'bsReload:all']
            },
            css: {
                files: 'src/scss/**/*.scss',
                tasks: ['sass', 'postcss', 'bsReload:css']
            },
            js: {
                files: 'src/js/**/*.js',
                //tasks: ['include_file', 'uglify', 'bsReload:all']
                tasks: ['include_file', 'uglify', 'bsReload:all']
            }
        },

        browserSync: {
            dev: {
                options: {
                    open: false,
                    watchTask: true,
                    server: {
                        baseDir: 'web'
                    },
                    notify: {
                        styles:  [
                            "display: none",
                            "padding: 15px",
                            "font-family: sans-serif",
                            "position: fixed",
                            "font-size: 0.9em",
                            "z-index: 9999",
                            "bottom: 0px",
                            "left: 0px",
                            "border-bottom-left-radius: 5px",
                            "background-color: #1B2032",
                            "margin: 0",
                            "color: white",
                            "text-align: center"
                        ]
                    }
                }
            }
        },

        bsReload: {
            css: {
                reload: 'css/style.css'
            },
            all: {
                reload: true
            }
        },

        webfont: {
         icons: {
             src: 'src/icons/*.svg',
             dest: 'fonts/',
             destCss: 'src/scss/partials/',
             options: {
                 stylesheet: 'scss',
                 relativeFontPath: '../fonts',
                 template: 'src/scss/partials/_icons-template.scss',
                 types: 'eot,woff,ttf,svg',
                 htmlDemo: false,
                 optimize: false
             }
            }
        },

        scsslint: {
            allFiles: [
                'src/scss/*/**.scss',
            ],
            options: {
                config: '.scss-lint.yml',
                reporterOutput: null,
                colorizeOutput: true
            },
        }

    });

    grunt.loadNpmTasks('grunt-scss-lint');
    grunt.loadNpmTasks('grunt-browser-sync');

    grunt.registerTask('styles', ['sass', 'postcss']);
    grunt.registerTask('default', ['browserSync', 'watch']);
};