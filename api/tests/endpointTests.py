import unittest
import requests


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
        createResult = self.create ('sport', {'name':'bouncyball'})
        self.assertIsNotNone (createResult)
        self.assertEqual (200, createResult.status_code)

        id = createResult.json() ['id']

        getResult = self.get ('sport', id)
        self.assertEqual ('bouncyball', getResult.json() ['name'])
        self.assertEqual (200, getResult.status_code)

        updateResult = self.update ('sport', id, {'name':'something else'})
        self.assertEqual (200, updateResult.status_code)

        getResult = self.get ('sport', id)
        self.assertEqual ('something else', getResult.json() ['name'])
        self.assertEqual (200, getResult.status_code)

        deleteResult = self.delete ('sport', id)
        self.assertEqual (204, deleteResult.status_code)

        getResult = self.get ('sport', id)
        self.assertEqual (404, getResult.status_code)
    def test_game (self):
        pass
    def test_locationType (self):
        pass


if (__name__ == '__main__'):
    unittest.main()