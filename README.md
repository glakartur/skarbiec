# skarbiec
System wspierający zadania skarbnika klasowego

## narzędzia
skarbiec.py ACTION
* ACTIONS:
  * init - inicjalizacja katalogu na lokalną kopię danych skarbca
  * import-transactions - import transakcji z konta bankowego do skarbca

## witryna skarbca
Kod witryny znajduje się w katalogu [web](./web).

## dane
Na lokalnym dysku należy utworzyć i zainicjować katalog na dane skarbca.
Pełni on zarówno funkcję źródła transakcji na koncie bankowym jak i lokalnej kopii danych z witryny skarbca.

### transakcje z konta bankowego
Wyciągi z konta należy wrzucać do podfolderu *Transakcje*.
Uruchomienie narzędzia importu powoduje wykrycie nowych transakcji i przeniesienie ich do pliku *transactions.json* w podkatalogu *data*.
Następnie należy wyeksportować ten plik na serwer webowy witryny skarbca.
Ten plik nie jest modyfikowany po stronie witryny webowej.

### lokalna kopia danych
W celu wykonania lokalnej kopii danych z serwera skarbca należy skopiować pliki z katalogu *data* (z pominięciem plików eksportowanych na serwer).
