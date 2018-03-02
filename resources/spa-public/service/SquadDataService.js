
import Connection from '../core/Connection';

const API_BASE = '/api/squad/';

export default class SquadDataService {
	
	constructor()
	{
		this.Connection = new Connection({ baseURL: API_BASE });
		return this;
	}
	
}
