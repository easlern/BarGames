import unittest
import requests
import json


class EndpointTests (unittest.TestCase):
    def setUp (self):
        self.url = 'http://www.expirednews.net/api/'
    def delete (self, model, id):
        return requests.delete (self.url + model + '/' + str (id) + '/?csrfTokenOverride')
    def create (self, model, params):
        return requests.post (self.url + model + '?csrfTokenOverride', params)
    def update (self, model, id, params):
        return requests.put (self.url + model + '/' + str (id) + '/?csrfTokenOverride', params)
    def get (self, model, id):
        return requests.get (self.url + model + '/' + str (id) + '/?csrfTokenOverride')
    def test_sport (self):
        model = 'sport'
        createResult = self.create (model, {'name':'bouncyball'})
        self.assertIsNotNone (createResult)
        self.assertEqual (200, createResult.status_code)

        id = createResult.json() ['id']

        getResult = self.get (model, id)
        self.assertEqual ('bouncyball', getResult.json() ['name'])
        self.assertEqual (200, getResult.status_code)

        updateResult = self.update (model, id, {'name':'something else'})
        self.assertEqual (200, updateResult.status_code)

        getResult = self.get (model, id)
        self.assertEqual ('something else', getResult.json() ['name'])
        self.assertEqual (200, getResult.status_code)

        deleteResult = self.delete (model, id)
        self.assertEqual (204, deleteResult.status_code)

        getResult = self.get (model, id)
        self.assertEqual (404, getResult.status_code)
    def test_game (self):
        sportId = self.create ('sport', {'name':'bouncyball'}).json() ['id']
        cityId = self.create ('city', {'name':'Grand Rapids', 'state':'MI', 'country':'US', 'latitude':'123.45', 'longitude':'43.21'}).json() ['id']
        locationId = self.create ('location', {'name':'sock hop', 'street':'123 main', 'cityId':cityId, 'phone':'6168675309'}).json() ['id']
        teamIds = list()
        teamIds.append (self.create ('team', {'name':'louies losers'}).json() ['id'])
        teamIds.append (self.create ('team', {'name':'bad news bears'}).json() ['id'])
        model = 'game'
        game = self.create (model, {'name':'rumble in the jungle', 'locationId':locationId, 'sportId':sportId, 'teamIds':json.dumps (teamIds)})
        self.assertEqual (game.json() ['sportId'], sportId)
        self.assertEqual (200, game.status_code)
        gameId = game.json() ['id']

        game = self.get (model, gameId)
        self.assertEqual (200, game.status_code)
        self.assertEqual (teamIds, game.json() ['teamIds'])

        game = self.update (model, gameId, {'name':'rumble in the bronx', 'teamIds':'[]'})
        self.assertEqual (200, game.status_code)
        game = self.get (model, gameId)
        self.assertEqual (200, game.status_code)
        self.assertEqual ('rumble in the bronx', game.json() ['name'])
        self.assertEqual ([], game.json() ['teamIds'])

        game = self.delete (model, gameId)
        self.assertEqual (204, game.status_code)

        self.delete ('location', locationId)
        self.delete ('city', cityId)
        self.delete ('sport', sportId)
    def test_city (self):
        model = 'city'
        createResult = self.create (model, {'name':'Grand Rapids','state':'MI','country':'US','latitude':85.6,'longitude':42.9})
        self.assertIsNotNone (createResult)
        self.assertEqual (200, createResult.status_code)

        id = createResult.json() ['id']

        getResult = self.get (model, id)
        self.assertEqual ('Grand Rapids', getResult.json() ['name'])
        self.assertAlmostEqual (85.6, getResult.json() ['latitude'], 1)
        self.assertAlmostEqual (42.9, getResult.json() ['longitude'], 1)
        self.assertEqual (200, getResult.status_code)

        updateResult = self.update (model, id, {'name':'Beer City'})
        self.assertEqual (200, updateResult.status_code)

        getResult = self.get (model, id)
        self.assertEqual ('Beer City', getResult.json() ['name'])
        self.assertEqual (200, getResult.status_code)

        deleteResult = self.delete (model, id)
        self.assertEqual (204, deleteResult.status_code)

        getResult = self.get (model, id)
        self.assertEqual (404, getResult.status_code)


if (__name__ == '__main__'):
    unittest.main()