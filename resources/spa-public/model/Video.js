
import VideoDataService from '../service/VideoDataService';
import Model from "../core/Model";

export default class Video extends Model {

	constructor()
	{
		super();
		
		this.DataService = new VideoDataService();
		this.DB = this.DataService.Connection;
	
		this.id = null;
		
		return this;
	}
	
}
