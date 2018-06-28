
import GalleryDataService from '../service/GalleryDataService';
import Model from "../core/Model";

export default class Gallery extends Model {

	constructor()
	{
		super();
		
		this.DataService = new GalleryDataService();
		this.DB = this.DataService.Connection;
	
		this.id = null;
		this.name = '';
		this.active = '';
		
		return this;
	}
	
	fetchImages( galleryId )
	{
		return this.DB.get('fetchImages', { params: { id: galleryId }, headers: {'Content-Type': 'application/json'} })
			.then( response => response.data )
			.catch( error => error );
	}
	
}


