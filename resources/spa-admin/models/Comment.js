
import ArticleDataService from '../services/CommentDataService';
import Model from "../core/Model";

export default class Comment extends Model {

	constructor()
	{
		super();
		
		this.DataService = new CommentDataService();
		this.DB = this.DataService.Connection;
	
		this.id = null;
		
		return this;
	}
	
}
