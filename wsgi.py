#!/usr/bin/env python


import time
import sys
import os
import json
from flask import Flask, Request, Response, render_template
application = app = Flask('wsgi')
app.jinja_env.add_extension('pyjade.ext.jinja.PyJadeExtension')

@app.route('/')
def welcome():
    #return 'welcome to appfog!'
    return render_template('index.jade', name="test")

@app.route('/env')
def env():
    return os.environ.get("VCAP_SERVICES", "{}")

@app.route('/import')
def import_csv():
    import csv, codecs
    data= open(url_for("static", filename="in.csv"))
    reader=csv.DictReader(data, delimiter=",", quotechar='"')
    for r in reader:
        for k, v in r.items():
            if not v:
                r[k]=None
            body=r
            print (body)
    return body
                        

@app.route('/mongo')
def mongotest():
    from pymongo import Connection
    uri = mongodb_uri()
    conn = Connection(uri)
    coll = conn.db['ts']
    coll.insert(dict(now=int(time.time())))
    last_few = [str(x['now']) for x in coll.find(sort=[("_id", -1)], limit=10)]
    body = "\n".join(last_few)
    return Response(body, content_type="text/plain;charset=UTF-8")

def mongodb_uri():
    local = os.environ.get("MONGODB", None)
    if local:
        return local
    services = json.loads(os.environ.get("VCAP_SERVICES", "{}"))
    if services:
        creds = services['mongodb-1.8'][0]['credentials']
        uri = "mongodb://%s:%s@%s:%d/%s" % (
            creds['username'],
            creds['password'],
            creds['hostname'],
            creds['port'],
            creds['db'])
        print >> sys.stderr, uri
        return uri
    else:
        raise Exception, "No services configured"
    

if __name__ == '__main__':
    app.run(debug=True)
