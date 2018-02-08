
import Connection from '../core/Connection';

const API_BASE = '/api/season/';

export default class SeasonDataService {
	
	constructor()
	{
		this.Connection = new Connection({ baseURL: API_BASE });
		return this;
	}
	
}
