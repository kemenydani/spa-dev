
import ArticleDataService from '../service/ArticleDataService';
import Model from "../core/Model";

export default class Article extends Model {

	constructor()
	{
		super();
		
		this.DataService = new ArticleDataService();
		this.DB = this.DataService.Connection;

		return this;
	}
	
}


