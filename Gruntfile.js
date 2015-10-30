'use strict';

module.exports = function (grunt) {
    require('time-grunt')(grunt);
    require('load-grunt-tasks')(grunt);

    grunt.initConfig({
        clean:{
            server:'.tmp'
        }
      , replace:{
            app:{
                options:{
                    variables:{
                        ember:'vendor/ember/ember.js'
                      , ember_data:'vendor/ember-data/ember-data.js'
                    }
                }
              , files:[{
                    src: 'app/index.html'
                  , dest: '.tmp/index.html'
                }]
            }
        }
      , concurrent:{
            server:[
                'emberTemplates'
              , 'less:server'
            ]
        }
      , emberTemplates:{
            options:{
                templateName:function(sourceFile){
                    return sourceFile.replace('app/templates/','');
                }
            }
          , dist:{
                files:{
                '.tmp/scripts/compiled-templates.js':'app/templates/**/*.hbs'
                }
            }
        }
      , neuter:{
            app:{
                options:{
                    filepathTransform:function(filepath){
                        return 'app/'+filepath;
                    }
                }
              , src:'app/scripts/app.js'
              , dest:'.tmp/scripts/combined-scripts.js'
            }
        }
      , less:{
            server:{
                options:{
                    dumpLineNumbers:'all'
                  , paths:['bower_components']
                }
              , files:{
                    '.tmp/styles/layout1.css':'app/styles/layout1.less'
                  , '.tmp/styles/layout2.css':'app/styles/layout2.less'
                  , '.tmp/styles/layout3.css':'app/styles/layout3.less'
                  , '.tmp/styles/layout4.css':'app/styles/layout4.less'
                }
            }
        }
      , connect:{
            options:{
                port:9000
              , livereload:35729
              , hostname:'localhost'
            }
          , livereload:{
                options:{
                    base:['app']
                  , middleware:function(connect){
                        return [
                            connect.static('.tmp')
                          , connect.static('app')
                          , connect.static('media')
                          , connect().use('/vendor',
                                connect.static('bower_components'))
                          , connect().use('/data',
                                connect.static('data'))
                        ];
                    }
                }
            }
        }
      , watch:{
            emberTemplates:{
                files:'app/templates/**/*.hbs'
              , tasks:['emberTemplates']
            }
          , neuter:{
                files:['app/scripts/{,*/}*.js']
              , tasks:['neuter']
            }
          , less:{
                files:['app/styles/{,*/}*.less']
              , tasks:['less:server']
            }
          , livereload:{
                options:{
                    livereload:'<%= connect.options.livereload %>'
                }
              , files:[
                    '.tmp/scripts/*.js'
                  , '.tmp/styles/*.css'
                  , '.tmp/*.html'
                  , 'app/**/*.html'
                ]
            }
        }
    });

    grunt.registerTask('serve',[
        'clean:server'
      , 'replace:app'
      , 'concurrent:server'
      , 'neuter:app'
      , 'connect:livereload'
      , 'watch'
    ]);
};

