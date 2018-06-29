
import MatchDataService from '../service/MatchDataService';
import Model from "../core/Model";

export default class Match extends Model {

	constructor()
	{
		super();
		
		this.DataService = new MatchDataService();
		this.DB = this.DataService.Connection;
	
		return this;
	}
	
	getMaps( matchId )
	{
		return this.DB.get('getMaps', { params: { id: matchId }, headers: {'Content-Type': 'application/json'} })
			.then( response => response.data )
			.catch( error => error );
	}
	
	storeMaps( matchId, maps )
	{
		return this.DB.post('storeMaps', { maps: maps, id: matchId })
			.then( response => response.data )
			.catch( error => error );
	}
	
}
