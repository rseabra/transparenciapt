#!/bin/bash

# security setting - no unset variables may be used
#set -o nounset
# security setting - bailout on failure
#set -o errexit


TS="`date +%Y%m%d%H%M`"
#TS="200903161734"

# assume that directory is the same where this script is
DIR=`dirname $0`
if [ x${DIR} == x ]; then
    DIR=.
fi

DOC="$DIR/doc/$TS"
CSV="$DIR/csv/$TS"
DB="$DIR/db/$TS"
LIST="$DIR/list/$TS"
MAX="${LIST}-MAX"
LOG="$DIR/log/$TS"


echo -n "[ `date` ] Get document list... " > $LOG
"$DIR/get-doc-list.py" | sort -nu > "$LIST"
echo " done." >> $LOG

MAX=`tail -1 $LIST`
for i in `seq 1 $MAX` ; do
	echo $i >> $MAX
done

echo -n "[ `date` ] Get documents from document list" >> $LOG
mkdir -p "$DOC"
#	"$DIR/get-ads.pl" -update "$LIST" "$DOC" > "$LOG"
#	"$DIR/get-ads.pl" "$LIST" "$DOC" > "$LOG"
"$DIR/get-ads.pl" "$MAX" "$DOC" > "$LOG"
echo " done." >> $LOG

echo "[ `date` ] Parsing the obtained documents" >> $LOG
mkdir -p "$CSV"
ls $DOC | while read IDAD ; do
	echo -n "[ `date` ]	$IDAD..." >> $LOG
	"$DIR/parse-doc.pl" "$DOC/$IDAD" "$IDAD" > "$CSV/$IDAD"
	echo " done." >> $LOG
done

echo "[ `date` ] Join CSV data into one big file at $DB" >> $LOG
cat "$CSV"/* | iconv -c -f utf-8 -t iso-8859-15 > "$DB"
echo " done." >> $LOG

echo "[ `date` ] Load $DB into DB" >> $LOG
"$DIR/db-load.pl" < "$DB"
echo " done." >> $LOG

# mudar para algo nao arriscado como isto
##mysqldump -u user -p transparenciaptorg ad | bzip2 -c > /tmp/snapshot-200901272141.sql.bz2
#
echo " done. Over and out." >> $LOG
