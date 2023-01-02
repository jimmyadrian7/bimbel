const path = require('path');

module.exports = {
	entry: {
		app: path.resolve(__dirname, "Frontend/main.js")
	},
	output: {
		filename: "[name].bundle.js",
		path: path.resolve(__dirname, 'public/angular')
	},
	module: {
		rules: [
			{
				test: /\.css$/,
				use: ['style-loader', 'css-loader']
			},
			{
				test: /\.html$/,
				use: ['html-loader']
			}
		]
	}
}