# Warehouse_app

![obraz](https://user-images.githubusercontent.com/61948821/200008946-61854959-adfa-4c6e-98c9-40caa864a82e.png)

# Opis działania aplikacji

1. Po zalogowaniu aplikacja przenosi użytkownika do panelu, na którym wypisane są w tabeli dostępne magazyny.
2. W prawym górnym rogu znajduje się menu, które umożliwia wylogowanie się, bądź przejście do panelu administratora w przypadku posiadania uprawnień.
3. Jeżeli użytkownik przejdzie do magazynu, wyświetlą się artykuły, które aktualnie się w nim znajdują.
4. Menu wyświetla dodatkowe opcje: przyjęcia artykułu, historii przyjęć i wydania artykułu.

![obraz](https://user-images.githubusercontent.com/61948821/200010636-ba06adaf-4c54-4328-b9fc-224034402b4b.png)

5. Przyjęcie i wydanie artykułu odbywa się poprzez uzupełnienie formularza.
6. Przyjęte artykuły sumowane są w tabeli magazynu, natomiast po wydaniu ubywają.
7. Historia przyjęć pozwala na dokładne przejrzenie każdego formularza, włączając w to faktury.
8. Panel administratora zawiera pasek nawigacyjny z: dodawaniem użytkowników, dodawaniem magazynów, dodawaniem produktów, użytkownikami, magazynami.
9. Dodawanie użytkowników, magazynów i produktów odbywa się poprzez użycie formularza.
10. Zakłada użytkownicy i magazyny wyświetla tabelę, z której użytkownik przenosi się do panelu dodawania użytkowników do magazynów, bądź odwrotnie, odbywa się to poprzez kliknięcie w napis "Dodaj magazyn/użytkownika".

# Opis wykorzystanch metod z repository

Podstawowe metody: find, findOneBy, findAll, findBy, save, remove

1.MembershipWarehouseRepository:

showAssignedWarehouse - metoda przyjmuje id, na podstawie którego pobiera i zwraca id magazynów przypisanych do użytkownika o podanym id.

showAssignedUser - metoda działa analogicznie do showAssignedWarehouse, tylko w drugą stronę.

2. ProductPropertiesRepository:

getLastPropertiesId - pobiera najwyższe id i zwraca o 1 większe, czyli te, które zostanie nadane następnemu rekordowi.

# Opis działania metod w katalogu Service

1. AdminService - klasa przyjmująca w konstruktorze repozytoria wykorzystanch tabel, wykorzystana w kontrolerze panelu administratora.

addUser - metoda przyjmująca formularz i request, obsługuje formularz, sprawdza czy użytkownik istnieje, a następnie go zapisuje, kolejno pobierane są dane z selecta magazynów i zapisywane w tabeli odpowiedzialnej za relacje ManyToMany między tabelą użytkownika, a tabelą magazynu.

addWarehouse - metoda działająca analogicznie do addUser, tylko w drugą stronę.

addProduct - metoda przyjmująca formularz i requesta, obsługuje formularz i zapisuje obiekt artykułu.

showUsers, showWarehouses - metody zwracające wszystkie rekordy tabel użytkowników i magazynów.

showUser, showWarehouse - metody zwracające użytkownika i magazyn po id.

showAssignedWarehouse - metoda przyjmuje id, następnie prównuje wszystkie megazyny z tymi, które są już przypisane, następnie usuwa z tablicy przypisane magazyny i zwraca tylko te dostępne.

showAssignedUser - metoda działa analogicznie do showAssignedWarehouse, tylko na użytkownikach.

updateMembership - metoda przyjmuje id użytkownika i magazynu, zapisuje relacje i zwraca prawdę.

2. ReceiptProductService - klasa przyjmuje w konstruktorze repozytoria tabel odpowiadających za przyjmowanie i wydawanie artykułów, oraz menedżer encji, wykorzystana w kontrolerze panelu użytkownika.

receiptProduct - metoda przyjmuje: formularz, request, ścieżkę zapisu pliku i obiekt magazynu, następnie obsługuje formularz, tworzy obiekt ProductProperties odpowiedzialny za zapisywanie historii pobrań artykułu, kolejno wyszukuje rekordu z aktualnym stanem artykułu na magazynie, jeżeli artykuł nie istnieje, to zostaje utworzony, jeżeli istnieje zostaje dodana do niego przyjęta wartość. Następnie sprawdzana jest ilość wysłanych plików, kolejno w pętli sprawdzane są rozszerzenia, jeżeli są poprawne to zostają zapisane w menedżerze encji, ale zapisane zostają dopiero po nieprzerwalnym wykonaniu pętli, wraz z pozostałymi obiektami.

showState - metoda przyjmuje magazyn i zwraca jego stan.

showProperties - metoda przyjmuje magazyn i zwraca historię przyjęć.

showPropertiesDetails - metoda przyjmuje id, na podstawie którego zwraca obiekt historii przyjęć.

releaseProduct - metoda przyjmuje formularza, request i magazyn, obsługuje formularz, pobiera obiekt statusu magazynu, sprawdza czy obiekt istnieje, następnie czy wartość quantity nie jest mniejsza od przesłanej w formularzu, kolejno aktualizuje status magazynu.

3. WarehouseService - klasa przyjmuje w konstruktorze repozytoria użytkowników, magazynów i tabelę odpowiedzialną za relację między nimi, wykorzystana w kontrolerze panelu użytkownika.

showWarehouses - metoda przyjmuje id użytkownika i rangę, sprawdza czy ranga to administrator, jeżeli tak, to zwraca wszystkie magazyny, jeżeli nie, to tworzy i zwraca tablicę z magazynami przypisanymi do id użytkownika.

showWarehouse - metoda przyjmuje id i po nim zwraca magazyn.

checkAccess - metoda przyjmuje id użytkownika, id magazynu i rangę, sprawdza czy ranga to administrator, jeżeli tak, zwraca wartość true, w przeciwnym wypadku sprawdza czy w tabeli istnieje powiązanie między użytkownikiem i magazynem, jeżeli tak, zwraca prawde, jeżeli nie, zwraca fałsz.
