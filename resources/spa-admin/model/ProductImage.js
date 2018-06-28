
import Model from "../core/Model";
import ProductImageDataService from "../service/ProductImageDataService";

export default class ProductImage extends Model {

	constructor()
	{
		super();
		
		this.DataService = new ProductImageDataService();
		this.DB = this.DataService.Connection;
		
		return this;
	}
	
}


