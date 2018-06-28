
import GalleryImageDataService from '../service/GalleryImageDataService';
import Model from "../core/Model";

export default class GalleryImage extends Model {

	constructor()
	{
		super();
		
		this.DataService = new GalleryImageDataService();
		this.DB = this.DataService.Connection;
		
		return this;
	}
	
}


