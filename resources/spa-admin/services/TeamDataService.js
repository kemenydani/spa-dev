
import Connection from '../core/Connection';

const API_BASE = '/api/team/';

export default class TeamDataService {
	
	constructor()
	{
		this.Connection = new Connection({ baseURL: API_BASE });
		return this;
	}
	
}
