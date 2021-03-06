
import ProductDataService from '../service/ProductDataService';
import Model from "../core/Model";

export default class Product extends Model {

	constructor()
	{
		super();
		
		this.DataService = new ProductDataService();
		this.DB = this.DataService.Connection;
	
		return this;
	}
	
	fetchImages( productId )
	{
		return this.DB.get('fetchImages', { params: { id: productId }, headers: {'Content-Type': 'application/json'} })
			.then( response => response.data )
			.catch( error => error );
	}

}


