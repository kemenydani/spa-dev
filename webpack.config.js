/* multi */
var path = require('path')
var webpack = require('webpack')

var ENV = process.env.NODE_ENV;

var configs = {
	'spa-admin'  :
	{
		entry: './resources/spa-admin/main.js',
		output: {
			path: path.resolve(__dirname, './public/spa-admin/dist'),
			publicPath: '/public/spa-admin/dist/',
			filename: 'build.admin.js'
		},
		devServer: {
			contentBase: path.resolve(__dirname, './public/spa-admin'),
			historyApiFallback: true,
			port: 9001,
			allowedHosts: [
				'http://php_app/',
			],
			disableHostCheck: true,
			proxy: [{
				context: ["/api"],
				target: "http://php_app/",
				changeOrigin: true,
			}]
		},
	},
	'spa-public' :
	{
		entry: './resources/spa-public/main.js',
		output: {
			path: path.resolve(__dirname, './public/spa-public/dist'),
			publicPath: '/public/spa-public/dist/',
			filename: 'build.public.js'
		},
		devServer: {
			contentBase: path.resolve(__dirname, './public/spa-public'),
			historyApiFallback: true,
			port: 8001,
			allowedHosts: [
				'http://php_app/',
			],
			disableHostCheck: true,
			proxy: [{
				context: ["/api"],
				target: "http://php_app/",
				changeOrigin: true,
			}]
		},
	},
};

var exportable = {
	module: {
		rules: [
			{
				test: /\.css$/,
				use: [
					'vue-style-loader',
					'css-loader'
				],
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

if(ENV === 'development')            Object.assign(exportable, configs['spa-admin']);
if(ENV === 'development-spa-admin')  Object.assign(exportable, configs['spa-admin']);
if(ENV === 'development-spa-public') Object.assign(exportable, configs['spa-public']);

if(ENV === 'production')            Object.assign(exportable, configs['spa-admin']);
if(ENV === 'production-spa-admin')  Object.assign(exportable, configs['spa-admin']);
if(ENV === 'production-spa-public') Object.assign(exportable, configs['spa-public']);

module.exports = exportable;

if (ENV.includes('production'))
{
  module.exports.devtool = '#source-map';
  
  module.exports.plugins = (module.exports.plugins || []).concat([
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
    })
  ])
}
