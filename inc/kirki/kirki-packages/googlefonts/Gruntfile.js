/* global module */
module.exports = function( grunt ) {

	grunt.initConfig( {

		// Get json file from the google-fonts API
		http: {
			'google-fonts-alpha': {
				options: { url: 'https://www.googleapis.com/webfonts/v1/webfonts?sort=alpha&key=AIzaSyCDiOc36EIOmwdwspLG3LYwCg9avqC5YLs' },
				dest: 'src/webfonts-alpha.json'
			},
			'google-fonts-popularity': {
				options: { url: 'https://www.googleapis.com/webfonts/v1/webfonts?sort=popularity&key=AIzaSyCDiOc36EIOmwdwspLG3LYwCg9avqC5YLs' },
				dest: 'src/webfonts-popularity.json'
			},
			'google-fonts-trending': {
				options: { url: 'https://www.googleapis.com/webfonts/v1/webfonts?sort=trending&key=AIzaSyCDiOc36EIOmwdwspLG3LYwCg9avqC5YLs' },
				dest: 'src/webfonts-trending.json'
			}
		}
	} );

	grunt.loadNpmTasks( 'grunt-http' );
	grunt.registerTask( 'googlefontsProcess', function() {
		var alphaFonts,
			popularityFonts,
			trendingFonts,
			finalObject = {
				items: {},
				order: {
					alpha: [],
					popularity: [],
					trending: []
				}
			},
			finalJSON,
			i,
			fontFiles = {},
			fontNames = [];

		// Get file contents.
		alphaFonts      = grunt.file.readJSON( 'src/webfonts-alpha.json' );
		popularityFonts = grunt.file.readJSON( 'src/webfonts-popularity.json' );
		trendingFonts   = grunt.file.readJSON( 'src/webfonts-trending.json' );

		// Add the alpha order.
		for ( i = 0; i < alphaFonts.items.length; i++ ) {
			finalObject.order.alpha.push( alphaFonts.items[ i ].family );
		}

		for ( i = 0; i < popularityFonts.items.length; i++ ) {

			// Populate the fonts.
			finalObject.items[ alphaFonts.items[ i ].family ] = {
				family: alphaFonts.items[ i ].family,
				category: alphaFonts.items[ i ].category,
				variants: alphaFonts.items[ i ].variants.sort()
			};

			// Add the popularity order.
			finalObject.order.popularity.push( popularityFonts.items[ i ].family );
			fontNames.push( popularityFonts.items[ i ].family );
		}

		// Add the trending order.
		for ( i = 0; i < trendingFonts.items.length; i++ ) {
			finalObject.order.trending.push( trendingFonts.items[ i ].family );
		}

		// Generate the font-files object.
		for ( i = 0; i < popularityFonts.items.length; i++ ) {
			fontFiles[ popularityFonts.items[ i ].family ] = popularityFonts.items[ i ].files;
		}

		// Write the final object to json.
		finalJSON = JSON.stringify( finalObject );
		grunt.file.write( 'src/webfonts.json', finalJSON );
		grunt.file.write( 'src/webfont-names.json', JSON.stringify( fontNames ) );
		grunt.file.write( 'src/webfont-files.json', JSON.stringify( fontFiles ) );

		// Delete files no longer needed.
		grunt.file.delete( 'src/webfonts-alpha.json' ); // jshint ignore:line
		grunt.file.delete( 'src/webfonts-popularity.json' ); // jshint ignore:line
		grunt.file.delete( 'src/webfonts-trending.json' ); // jshint ignore:line
	} );
	grunt.registerTask( 'googlefonts', function() {
		grunt.task.run( 'http' );
		grunt.task.run( 'googlefontsProcess' );
	} );
	grunt.registerTask( 'default', [ 'googlefonts' ] );
};
