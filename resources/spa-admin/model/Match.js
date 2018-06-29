
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
	
	storeMap( data )
    {
        return this.DB.post('storeMap', { data : data })
            .then( response => response.data )
            .catch( error => error );
    }

    storeMaps( data )
    {
        return this.DB.post('storeMaps', { data : data })
            .then( response => response.data )
            .catch( error => error );
    }

    deleteMap( id )
    {
        return this.DB.post('deleteMap', { id : id })
            .then( response => response.data )
            .catch( error => error );
    }
	
}
