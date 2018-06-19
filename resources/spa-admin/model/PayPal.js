
import PayPalDataService from '../service/PayPalDataService';
import Model from "../core/Model";

export default class PayPal extends Model {

	constructor()
	{
		super();
		
		this.DataService = new PayPalDataService();
		this.DB = this.DataService.Connection;
		
		return this;
	}
	
}
