import requests

url = "https://www.arcgis.com/sharing/rest/oauth2/token"

payload = {'client_id': 'DYpgDVdyGdLKpRJI',
'client_secret': '6a7ceab0887e400baf51378c92152377',
'grant_type': 'client_credentials'}
files = [

]
headers= {}

response = requests.request("POST", url, headers=headers, data = payload, files = files)

print(response.text.encode('utf8'))