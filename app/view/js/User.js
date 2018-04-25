
function User(props = {})
{
		self.props = props || {};
		
		this.getProperty = function(name)
		{
				return this.props.hasOwnProperty(name) ? this.props[name] : null;
		}
		.bind(this);
		
		this.getProfilePicture = function(){
			return this.getProperty('profile_picture');
		}
		.bind(this);
	
}