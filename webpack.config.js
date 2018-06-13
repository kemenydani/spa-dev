var ExtractTextPlugin = require("extract-text-webpack-plugin");

/* multi */
let path = require('path');
let webpack = require('webpack');

let ENV = process.env.NODE_ENV;

let configs = [
	{
		name: 'spa-admin',
		entry: './resources/spa-admin/main.js',
		output: {
			path: path.resolve(__dirname, './public/spa-admin/dist'),
			publicPath: '/public/spa-admin/dist/',
			filename: 'build.admin.js'
		},
		devServer: {
			contentBase: path.resolve(__dirname, './public/spa-admin'),
			historyApiFallback: true,
			hot: true,
			open: true,
			port: 9001,
			allowedHosts: [
				'http://phpapp/',
			],
			disableHostCheck: true,
			proxy: [{
				context: ["/api"],
				target: "http://phpapp/",
				changeOrigin: true,
			}]
		},
	},
	{
		name: 'spa-public',
		entry: './resources/spa-public/main.js',
		output: {
			path: path.resolve(__dirname, './public/spa-public/dist'),
			publicPath: '/public/spa-public/dist/',
			filename: 'build.public.js'
		},
		devServer: {
			contentBase: path.resolve(__dirname, './public/spa-public'),
			historyApiFallback: true,
			hot: true,
			open: true,
			port: 8001,
			allowedHosts: [
				'http://phpapp/',
			],
			disableHostCheck: true,
			proxy: [{
				context: ["/api"],
				target: "http://phpapp/",
				changeOrigin: true,
			}]
		},
	},
];

let exportable = {
	module: {
		rules: [
			{
				test: /\.css$/,
				loader: ExtractTextPlugin.extract({
					use: 'css-loader',
					fallback: 'vue-style-loader'
				})
				/*
				use: [
					'vue-style-loader',
					'css-loader'
				],
				*/
			},
			{
				test: /\.scss$/,
				use: [
					'vue-style-loader',
					'css-loader',
					'sass-loader'
				],
			},
			{
				test: /\.sass$/,
				use: [
					'vue-style-loader',
					'css-loader',
					'sass-loader?indentedSyntax'
				],
			},
			{
				test: /\.vue$/,
				loader: 'vue-loader',
				options: {
					loaders: {
						// Since sass-loader (weirdly) has SCSS as its default parse mode, we map
						// the "scss" and "sass" values for the lang attribute to the right configs here.
						// other preprocessors should work out of the box, no loader config like this necessary.
						'scss': [
							'vue-style-loader',
							'css-loader',
							'sass-loader'
						],
						'sass': [
							'vue-style-loader',
							'css-loader',
							'sass-loader?indentedSyntax'
						]
					}
					// other vue-loader options go here
				}
			},
			{
				test: /\.js$/,
				loader: 'babel-loader',
				exclude: /node_modules/
			},
			{
				test: /\.(png|jpg|gif|svg)$/,
				loader: 'file-loader',
				options: {
					name: '[name].[ext]?[hash]'
				}
			}
		]
	},
	resolve: {
		alias: { 'vue$': 'vue/dist/vue.esm.js' },
		extensions: ['*', '.js', '.vue', '.json']
	},
	performance: { hints: false },
	devtool: '#eval-source-map',
};

let production_config = [
	new ExtractTextPlugin("style.min.css"),
	new webpack.DefinePlugin({
		'process.env': {
			NODE_ENV: '"production"'
		}
	}),
	new webpack.optimize.UglifyJsPlugin({
		sourceMap: true,
		compress: {
			warnings: false
		}
	}),
	new webpack.LoaderOptionsPlugin({
		minimize: true
	}),
];

// DEV

const startDevelopment = function( CONFIG_NAME )
{
		for (let config of configs)
		{
			if(config.name === CONFIG_NAME )
			{
				config = Object.assign( config, exportable );
				module.exports = config;
				break;
			}
		}
};

if (ENV === 'development-spa-admin')  startDevelopment('spa-admin');
if (ENV === 'development-spa-public') startDevelopment('spa-public');

// PROD

const startProduction = function( CONFIG_NAME )
{
	for (let i = 0; i < configs.length; i++)
	{
		if(configs[i].name === CONFIG_NAME )
		{
			configs[i] = Object.assign( configs[i], exportable );
			configs[i].devtool = '#source-map';
			configs[i].plugins = (configs[i].plugins || []).concat( production_config );
			// need to export the whole config on each iteration
			module.exports = configs[i];
			break;
		}
	}
};

if(ENV === 'production-spa-admin')  startProduction('spa-admin');
if(ENV === 'production-spa-public') startProduction('spa-public');
